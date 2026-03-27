<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Laravel\Ai\Files;
use Laravel\Ai\Image;

#[Signature('app:generate-icon {exercise} {notes?}')]
#[Description('Generate a icon for an exercise.')]
class GenerateIcon extends Command
{
    private function prompt(): string
    {
        $base = '
        Create a 512x512 square icon.
        A hyper-realistic, cinematic studio fitness photography shot of the attached model athlete performing a ['.$this->argument('exercise')."].
        Setting: The scene is set in a attached space with sleek matte black equipment if necessary.
        Lighting & Mood: Dramatic 'warm, muted cinematic color grade' lighting with subtle rim lights to define the athlete’s muscles. Moody atmosphere with a slight touch of volumetric fog.
        Position: 3/4 facing angle, showing the athlete's form and the exercise movement clearly. Dynamic pose that captures the essence of the exercise.
        Technical Specs: Shot on 85mm lens, f/1.8, shallow depth of field with a blurred gym background. High contrast, sharp focus on the athlete's form, 8k resolution, raw photo style. No text, no watermarks.
        Add blurred depth-of-field effect in the background
        Make sure the environment / character size and positions blend perfectly.";

        if ($this->argument('notes')) {
            $base .= "\n\nAdditional notes: ".$this->argument('notes');
        }

        return $base;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $image = Image::of($this->prompt())
            ->attachments([
                Files\Image::fromPath(resource_path('assetModels/male.png')),
                Files\Image::fromPath(resource_path('assetModels/environment.png')),
            ])
            ->square()
            ->generate();

        $image->storeAs('icons/exercise_icon_'.$this->argument('exercise').'.jpeg');
    }
}
