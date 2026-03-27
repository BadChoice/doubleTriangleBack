<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class GenerateExerciseIcon implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return "
        Create a 512x512 square icon.
        A hyper-realistic, cinematic fitness photography shot of the attached model athlete performing a [EXERCISE].
        Setting: The scene is set in a attached space with sleek matte black equipment if necessary.
        Lighting & Mood: Dramatic 'warm, muted cinematic color grade' lighting with subtle rim lights to define the athlete’s muscles. Moody atmosphere with a slight touch of volumetric fog.
        Technical Specs: Shot on 85mm lens, f/1.8, shallow depth of field with a blurred gym background. High contrast, sharp focus on the athlete's form, 8k resolution, raw photo style. No text, no watermarks.
        Add blurred depth-of-field effect in the background
        Make sure the environment / character size and positions blend perfectly.";
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }
}
