<?php

namespace Yungifez\AprilUI\Tests\Feature\Components;

use Illuminate\Support\Facades\Blade;
use PHPUnit\Framework\Attributes\DataProvider;
use Yungifez\AprilUI\Tests\TestCase;

class FormSubmissionTest extends TestCase
{
    #[DataProvider('textareaValues')]
    public function test_a_textarea_renders_its_initial_value(string $value): void
    {
        $html = Blade::render('<april:textarea name="description">{{ $value }}</april:textarea>', ['value' => $value]);

        $this->assertSame(1, preg_match('/<textarea\b[^>]*>(.*?)<\/textarea>/s', $html, $matches));
        $this->assertSame($value, html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertSame(1, substr_count($html, '</textarea>'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function textareaValues(): array
    {
        return [
            'empty' => [''],
            'existing value' => ['Existing description'],
            'multiple lines' => ["First line\n  Second line\nThird line"],
            'escaped content' => ['</textarea><script>alert("hello")</script> & notes'],
        ];
    }

    public function test_a_textarea_prefers_old_input_to_the_existing_value(): void
    {
        $session = $this->app['session.store'];
        $session->flashInput(['description' => 'Unsaved description']);
        $this->app['request']->setLaravelSession($session);

        $html = Blade::render('<april:textarea name="description">{{ old("description", $description) }}</april:textarea>', [
            'description' => 'Existing description',
        ]);

        $this->assertStringContainsString('>Unsaved description</textarea>', $html);
        $this->assertStringNotContainsString('Existing description', $html);
    }

    public function test_an_empty_textarea_still_forwards_livewire_attributes(): void
    {
        $html = Blade::render('<april:textarea wire:model="description" rows="6" placeholder="Description" />');

        $this->assertStringContainsString('wire:model="description"', $html);
        $this->assertStringContainsString('rows="6"', $html);
        $this->assertStringContainsString('placeholder="Description"', $html);
        $this->assertStringContainsString('></textarea>', $html);
    }

    public function test_an_alert_dialog_action_defaults_to_a_non_submitting_button(): void
    {
        $html = Blade::render('<april:alert-dialog-action>Continue</april:alert-dialog-action>');

        $this->assertStringContainsString('type="button"', $html);
        $this->assertSame(1, preg_match_all('/\btype="/', $html));
    }

    #[DataProvider('buttonTypes')]
    public function test_an_alert_dialog_action_uses_the_requested_button_type(string $type): void
    {
        $html = Blade::render('<april:alert-dialog-action :type="$type">Confirm</april:alert-dialog-action>', ['type' => $type]);

        $this->assertStringContainsString('type="'.$type.'"', $html);
        $this->assertSame(1, preg_match_all('/\btype="/', $html));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function buttonTypes(): array
    {
        return [
            'button' => ['button'],
            'submit' => ['submit'],
            'reset' => ['reset'],
        ];
    }

    public function test_a_submit_action_forwards_its_external_form_and_submitter_attributes(): void
    {
        $html = Blade::render('<april:alert-dialog-action type="submit" form="delete-program" name="intent" value="delete" class="h-12">Delete</april:alert-dialog-action>');

        $this->assertStringContainsString('type="submit"', $html);
        $this->assertSame(1, preg_match_all('/\btype="/', $html));
        $this->assertStringContainsString('form="delete-program"', $html);
        $this->assertStringContainsString('name="intent"', $html);
        $this->assertStringContainsString('value="delete"', $html);
        $this->assertStringContainsString('h-12', $html);
        $this->assertStringNotContainsString('h-10', $html);
        $this->assertStringContainsString('x-bind="action"', $html);
    }
}
