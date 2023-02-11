<?php

use AugustoMoura\LaravelToolkit\Mail\SimpleEmail;
use AugustoMoura\LaravelToolkit\Providers\LaravelToolkitServiceProvider;
use Illuminate\Support\Facades\Mail;
use Orchestra\Testbench\TestCase;

class SimpleEmailTest extends TestCase
{
	protected function getPackageProviders($app)
    {
        return [LaravelToolkitServiceProvider::class];
    }
	
	public function test_simple_email()
	{
		$mailable = new SimpleEmail('Title', 'The message.');
		$mailable->assertSeeInText('The message.');

		Mail::fake();

		Mail::to('example@example.com')->queue($mailable);

		Mail::assertQueued(SimpleEmail::class, function ($mail) {
			return $mail->hasTo('example@example.com') 
				&& $mail->subject == 'Title';
		});
	}
	
	public function test_simple_email_attachments()
	{
		$mailable = new SimpleEmail('Title', 
			'The message.', 
			['path/to/file', 'path/to/file2']
		);
		$mailable->build();
		$attachments = collect($mailable->attachments);

		$this->assertTrue($attachments->contains('file', 'path/to/file'));
		$this->assertTrue($attachments->contains('file', 'path/to/file2'));
	}
}