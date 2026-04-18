<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Laravel\Ai\Files;
use Laravel\Ai\Image;

#[Signature('app:generate-vertical-shot {exercise}')]
#[Description('Generate a icon for an exercise.')]
class GenerateVerticalExerciseShot extends Command
{
    private function prompt(): string
    {
        $base = "
Create a vertical (4:5) high-resolution fitness studio photograph of the attached athlete model performing:
[EXERCISE_NAME]

### FRAMING & LAYOUT (STRICT):
Portrait orientation (4:5 ratio)
Athlete centered horizontally
Athlete occupies ~60–70% of image height (do NOT fill the frame)
Leave clear empty space at the top (~15–20%) and bottom (~15–20%) for text overlays
Full body visible at all times (no cropping of limbs)
Camera angle: 3/4 view, facing slightly right

### ENVIRONMENT:
Modern home gym, industrial Nordic style
Concrete + white brick walls
Matte black fitness equipment
Minimal, clean, uncluttered background

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
For machine glute kickback, ensure:
Neutral spine (no lumbar arch)
Hips square (no rotation)
Controlled backward leg extension
Slight bend in supporting leg
Foot pushing backward through the heel


### SUBJECT INTEGRATION:
Correct anatomical proportions (no distortions)
Realistic interaction with equipment
Consistent lighting and shadows with environment
No texts at all

### STYLE:
Professional fitness photography
Sharp subject focus, especially glutes and legs
Clean commercial look
No artifacts, no extra limbs, no warped equipment";

        return str_replace("[EXERCISE_NAME]",  $this->argument('exercise'), $base);

    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $image = Image::of($this->prompt())
            ->attachments([
//                Files\Image::fromPath(resource_path('assetModels/male.png')),
                Files\Image::fromPath(resource_path('assetModels/female.png')),
                //Files\Image::fromPath(resource_path('assetModels/environment.png')),
            ])
            ->portrait()
            ->generate();

        $image->storeAs('vertical/exercise_vertical_'.$this->argument('exercise').'.jpeg');
    }
}
