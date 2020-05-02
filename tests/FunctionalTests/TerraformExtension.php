<?php

declare(strict_types=1);

namespace Incognito\FunctionalTests;

use PHPUnit\Runner\BeforeFirstTestHook;
use PHPUnit\Runner\AfterLastTestHook;
use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * Class TerraformExtension
 *
 * Useful for provisioning a temporary AWS Cognito User Pool for executing
 * functional tests against.
 *
 * Terraform must be installed to use this extension!
 *
 * @see https://terraform.io
 * @package Incognito\FunctionalTests
 */
class TerraformExtension implements BeforeFirstTestHook, AfterLastTestHook
{
    public function executeBeforeFirstTest(): void
    {
        echo "\n\n\nYay! Running terraform to create the test Cognito User Pool...\n\n\n";

        $this->verifyTerraformIsInstalled();
        $this->initTerraform();
        $this->runTerraformApply();
    }

    public function executeAfterLastTest(): void
    {
        echo "\n\n\nBye! Running terraform to destroy the test Cognito User Pool...\n\n\n";

        $this->runTerraformDestroy();
    }

    private function initTerraform(): void
    {
        $process = Process::fromShellCommandline('terraform init', __DIR__ . '/terraform');
        $process->mustRun();
    }

    private function runTerraformApply(): void
    {
        $process = new Process(['terraform', 'apply', '-auto-approve'], __DIR__ . '/terraform');
        $process->mustRun();
    }

    private function runTerraformDestroy(): void
    {
        $process = new Process(['terraform', 'destroy', '-auto-approve'], __DIR__ . '/terraform');
        $process->mustRun();
    }

    private function verifyTerraformIsInstalled(): void
    {
        $process = Process::fromShellCommandline('command -v terraform');
        $process->run();

        if (!$process->getOutput()) {
            throw new RuntimeException(
                "Unable to verify that Terraform is installed. Terraform must be installed to execute the functional test suite.\nFind instructions for installing Terraform here:\nhttps://terraform.io\n\n"
            );
        }
    }
}
