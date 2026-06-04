<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Laravel\Ai\Files;
use Laravel\Ai\Image;

#[Signature('app:generate-vertical-shot {gender} {exercise} {equipment} {instructions}')]
#[Description('Generate a icon for an exercise.')]
class GenerateVerticalExerciseShot extends Command
{
    private function prompt(): string
    {
        $base = '
Create a vertical (4:5) high-resolution fitness studio photograph of the attached athlete model performing:
[EXERCISE_NAME]

### FRAMING & LAYOUT (STRICT):
Portrait orientation (4:5 ratio)
Athlete centered horizontally
Athlete occupies ~50–60% of image height (do NOT fill the frame)
Leave clear empty space at the top (~20–25%) and bottom (~20–25%) for text overlays
Full body visible at all times (no cropping of limbs)
Camera angle: 3/4 view, facing slightly right

### ENVIRONMENT:
Modern home gym, industrial Nordic style
Concrete + white brick walls
Matte black fitness equipment
Minimal, clean, uncluttered background

### EQUIPMENT:
[EQUIPMENT]

### LIGHTING:
Natural daylight from a window (side lighting)
Bright, soft, diffused light
Slightly desaturated tones
Background slightly blurred (shallow depth of field)

### EXERCISE EXECUTION (CRITICAL – MUST BE CORRECT):
Exercise: [EXERCISE_NAME]
Demonstrate perfect beginner-safe form
Biomechanics must be accurate and realistic
No exaggerated or unsafe positions

These are the instructions of the exercise:
[INSTRUCTIONS]


### SUBJECT INTEGRATION:
Correct anatomical proportions (no distortions)
Realistic interaction with equipment
Consistent lighting and shadows with environment
No texts at all

### STYLE:
Professional fitness photography
Sharp subject focus, especially glutes and legs
Clean commercial look
No artifacts, no extra limbs, no warped equipment';

        $result = str_replace('[EXERCISE_NAME]', $this->argument('exercise'), $base);
        $result = str_replace('[EQUIPMENT]', $this->argument('equipment'), $base);
        $result = str_replace('[INSTRUCTIONS]', $this->argument('instructions'), $base);

        return $result;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gender = $this->argument('gender');
        $exercise = $this->argument('exercise');

        $image = Image::of($this->prompt())
            ->attachments([
                Files\Image::fromPath(resource_path("assetModels/{$gender}.png")),
            ])
            ->portrait()
            ->generate();

        $image->storeAs('vertical/exercise_vertical_'.$gender.'_'.$exercise.'.jpeg');
    }
}
