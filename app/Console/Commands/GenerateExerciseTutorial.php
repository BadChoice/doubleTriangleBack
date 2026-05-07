<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Laravel\Ai\Files;
use Laravel\Ai\Image;

#[Signature('app:generate-exercise-tutorial {exercise} {equipment} {instructions}')]
#[Description('Command description')]
class GenerateExerciseTutorial extends Command
{
    private function prompt(): string
    {
        $base = '
Using the attached reference image(s), generate a precise instructional fitness sequence for the exercise [EXERCISE_NAME].

REFERENCE PRIORITY:
- The person from the reference image must be preserved exactly (face, body proportions, hairstyle).

SEQUENCE:
Show exactly 2 phases from left to right
1) Start position
2) End position

All two must:
- use the exact same person
- use the exact same equipment
- be perfectly aligned horizontally
- have identical scale and framing

CAMERA:
- angle: strict 3/4 front
- full body visible
- consistent angle across all frames
- no perspective distortion or lens warping

EQUIPMENT:
[EQUIPMENT]

ENVIRONMENT:
- clean white and bright studio background
- soft, even lighting
- no shadows that hide joints
- soft shadows on the slightly reflective floor
- no texts at all
- Use sleek black material equipment if necessary

CLOTHING:
- fitted athletic outfit in matte black material
- no gloss, no reflections
- identical outfit in all frames

FORM ACCURACY (CRITICAL):
The movement must strictly follow correct biomechanics and safe exercise execution principles. Each phase must demonstrate proper form, joint alignment, and muscle engagement for the specific exercise. The sequence should clearly illustrate the key positions and transitions of the movement, ensuring that it can be easily understood and replicated by viewers.

EXERCISE INSTRUCTIONS:
[INSTRUCTIONS]

Enforce:
- anatomically correct joint angles
- correct posture (neutral spine unless specified)
- realistic balance and weight distribution
- correct interaction with equipment (grip, positioning, range of motion)

EQUIPMENT RULES (if applicable):
- correct scale relative to body
- physically plausible contact points
- no floating or misaligned objects

STRICT CONSTRAINTS:
- no extra limbs or distortions
- no motion blur
- no stylistic interpretation
- no text or overlays
- no variation between frames (only pose changes)
- no muscle overlays
- no text at all

STYLE:
clinical, realistic fitness photography used in professional training manuals
        ';

        $result = str_replace('[EXERCISE_NAME]', $this->argument('exercise'), $base);
        $result = str_replace('[EQUIPMENT]', $this->argument('equipment'), $result);
        $result = str_replace('[INSTRUCTIONS]', $this->argument('instructions'), $result);

        return $result;
    }

    public function handle()
    {
        $image = Image::of($this->prompt())
            ->attachments([
                // Files\Image::fromPath(resource_path('assetModels/male.png')),
                Files\Image::fromPath(resource_path('assetModels/female.png')),
                // Files\Image::fromPath(resource_path('assetModels/environment.png')),
            ])
            ->landscape()
            ->generate();

        $image->storeAs('tutorials/'.$this->argument('exercise').'.jpeg');
    }
}
