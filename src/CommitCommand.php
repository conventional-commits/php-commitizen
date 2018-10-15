<?php
declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen;

use Damianopetrungaro\PHPCommitizen\Exception\InvalidArgumentException;
use Damianopetrungaro\PHPCommitizen\Section\Body;
use Damianopetrungaro\PHPCommitizen\Section\Description;
use Damianopetrungaro\PHPCommitizen\Section\Footer;
use Damianopetrungaro\PHPCommitizen\Section\Scope;
use Damianopetrungaro\PHPCommitizen\Section\Subject;
use Damianopetrungaro\PHPCommitizen\Section\Type;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use function file_exists;
use function is_array;

class CommitCommand extends Command
{
    private const COMMAND_NAME = 'commit';

    private const COMMAND_DESCRIPTION = 'Create a new commit following conventional commits specs (https://conventionalcommits.org/)';

    private const OPTION_ADD_FILE_TO_STAGE_NAME = 'all';

    private const OPTION_ADD_FILE_TO_STAGE_SHORT_NAME = 'a';

    private const OPTION_ADD_FILE_TO_STAGE_DESCRIPTION = 'All the unstaged files will be added before creating the commit';

    private const ARGUMENT_PATH_TO_CONFIGURATION = 'config';

    private const ARGUMENT_PATH_TO_CONFIGURATION_DESCRIPTION = 'Specify a php file for override default configuration';

    private const EXTRA_KEY_NAME = 'personalized';

    /**
     * @var array
     */
    private $configuration;

    /**
     * @var QuestionHelper
     */
    private $questionHelper;

    /**
     * @var CreateConventionalCommit
     */
    private $createConventionalCommit;

    public function __construct(
        array $configuration,
        CreateConventionalCommit $createConventionalCommit,
        QuestionHelper $questionHelper = null
    )
    {
        parent::__construct(null);
        $this->configuration = $configuration;
        $this->createConventionalCommit = $createConventionalCommit;
        $this->questionHelper = $questionHelper ?: new QuestionHelper();
    }

    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::COMMAND_DESCRIPTION);
        $this->addArgument(
            self::ARGUMENT_PATH_TO_CONFIGURATION,
            null,
            self::ARGUMENT_PATH_TO_CONFIGURATION_DESCRIPTION
        );
        $this->addOption(
            self::OPTION_ADD_FILE_TO_STAGE_NAME,
            self::OPTION_ADD_FILE_TO_STAGE_SHORT_NAME,
            null,
            self::OPTION_ADD_FILE_TO_STAGE_DESCRIPTION
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = $this->loadConfiguration($input->getArgument(self::ARGUMENT_PATH_TO_CONFIGURATION));

        try {
            $type = $this->createType($input, $output, $configuration);
            $scope = $this->createScope($input, $output, $configuration);
            $description = $this->createDescription($input, $output, $configuration);
            $subject = Subject::build($type, $scope, $description, $configuration);
            $body = $this->createBody($input, $output, $configuration);
            $footer = $this->createFooter($input, $output, $configuration);
            $addAll = $input->getOption(self::OPTION_ADD_FILE_TO_STAGE_NAME);
            ($this->createConventionalCommit)($subject, $body, $footer, $addAll);
        } catch (InvalidArgumentException $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        }
    }

    private function loadConfiguration(?string $customConfigurationPath): Configuration
    {
        if ($customConfigurationPath === null) {
            return Configuration::fromArray($this->configuration);
        }

        if (!file_exists($customConfigurationPath)) {
            throw new InvalidArgumentException("Custom configuration file does not exists: '$customConfigurationPath'");
        }

        $customConfiguration = require $customConfigurationPath;

        if (!is_array($customConfiguration)) {
            throw new InvalidArgumentException('Custom configuration file must return an array');
        }

        return Configuration::fromArray(array_merge($this->configuration, $customConfiguration));
    }

    private function createType(InputInterface $input, OutputInterface $output, Configuration $configuration): Type
    {
        $output->writeln('<comment>Commits MUST be prefixed with a type, which consists of a noun, feat, fix, etc.</comment>');
        $output->writeln("<comment>Type length must be between {$configuration->minLengthType()} and {$configuration->maxLengthType()}</comment>");

        $typeValues = $configuration->types();
        if ($configuration->acceptExtraType() === true) {
            $typeValues[] = self::EXTRA_KEY_NAME;
        }

        if ($typeValues !== [self::EXTRA_KEY_NAME]) {
            $choice = new ChoiceQuestion("<question>Select commit's type:</question> ", $typeValues);
            $typeInput = $this->questionHelper->ask($input, $output, $choice);
        }

        if (!isset($typeInput) || $typeInput === self::EXTRA_KEY_NAME) {
            $question = new Question("<question>Enter a custom commit's type:</question> ", '');
            $typeInput = $this->questionHelper->ask($input, $output, $question);
        }

        return Type::build($typeInput, $configuration);
    }

    private function createScope(InputInterface $input, OutputInterface $output, Configuration $configuration): ?Scope
    {
        $scopeValues = $configuration->scopes();
        if ($scopeValues === [] && $configuration->acceptExtraScope() === false) {
            return null;
        }

        $output->writeln('<comment>An optional scope MAY be provided after a type. A scope is a phrase describing a section of the codebase.</comment>');
        $output->writeln("<comment>Scope length MUST be between {$configuration->minLengthScope()} and {$configuration->maxLengthScope()}</comment>");

        if ($configuration->acceptExtraScope() === true) {
            $scopeValues[] = self::EXTRA_KEY_NAME;
        }

        if ($scopeValues !== [self::EXTRA_KEY_NAME] && $scopeValues !== []) {
            $choice = new ChoiceQuestion("<question>Select commit's scope:</question> ", $scopeValues);
            $scopeInput = $this->questionHelper->ask($input, $output, $choice);
        }

        if (!isset($scopeInput) || $scopeInput === self::EXTRA_KEY_NAME) {
            $question = new Question("<question>Enter a custom commit's scope:</question> ", '');
            $scopeInput = $this->questionHelper->ask($input, $output, $question);
        }

        if ($scopeInput === '') {

            return null;
        }

        return Scope::build($scopeInput, $configuration);
    }

    private function createDescription(InputInterface $input, OutputInterface $output, Configuration $configuration): Description
    {
        $output->writeln('<comment>A description MUST immediately follow the type/scope prefix. The description is a short description of the changes</comment>');
        $output->writeln("<comment>Description length MUST be between {$configuration->minLengthDescription()} and {$configuration->maxLengthDescription()}</comment>");

        $question = new Question("<question>Enter a custom commit's description:</question> ", '');
        $descriptionInput = $this->questionHelper->ask($input, $output, $question);
        return Description::build($descriptionInput, $configuration);
    }

    private function createBody(InputInterface $input, OutputInterface $output, Configuration $configuration): Body
    {
        $output->writeln('<comment>A longer commit body MAY be provided after the short description.</comment>');

        $question = new Question("<question>Enter commit's body:</question> ", '');
        $bodyInput = $this->questionHelper->ask($input, $output, $question);
        return Body::build($bodyInput, $configuration);
    }

    private function createFooter(InputInterface $input, OutputInterface $output, Configuration $configuration): Footer
    {
        $output->writeln('<comment>A footer MAY be provided one blank line after the body. The footer SHOULD contain additional meta-information about the changes(such as the issues it fixes, e.g., fixes #13, #5).</comment>');

        $question = new Question("<question>Enter commit's footer:</question> ", '');
        $footerInput = $this->questionHelper->ask($input, $output, $question);
        return Footer::build($footerInput, $configuration);
    }
}
