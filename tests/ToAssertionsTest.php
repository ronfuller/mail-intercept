<?php

namespace Tests;

use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use KirschbaumDevelopment\MailIntercept\WithMailInterceptor;

class ToAssertionsTest extends TestCase
{
    use WithFaker,
        WithMailInterceptor;

    public function testMailSentToSingleEmail()
    {
        $this->interceptMail();

        $email = $this->faker->email;

        Mail::send([], [], function ($message) use ($email) {
            $message->to($email);
        });

        $mail = $this->interceptedMail()->first();

        $this->assertMailSentTo($email, $mail);
    }

    public function testMailSentToMultipleEmails()
    {
        $this->interceptMail();

        $emails = [
            $this->faker->email,
            $this->faker->email,
        ];

        Mail::send([], [], function ($message) use ($emails) {
            $message->to($emails);
        });

        $mail = $this->interceptedMail()->first();

        $this->assertMailSentTo($emails, $mail);
    }

    public function testDifferentMailSentToDifferentSingleEmail()
    {
        $this->interceptMail();

        $emails = [
            $this->faker->email,
            $this->faker->email,
        ];

        foreach ($emails as $email) {
            Mail::send([], [], function ($message) use ($email) {
                $message->to($email);
            });
        }

        $mails = $this->interceptedMail();

        foreach ($emails as $key => $email) {
            $this->assertMailSentTo($email, $mails[$key]);
        }
    }
}
