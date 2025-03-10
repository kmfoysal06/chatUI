<?php
  /**
   * Main Chatbot class that handles all the conversation logic
   */

class Chatbot {
    private array $patternGroups = [];

    public function __construct() {
        // Initialize the chatbot with default patterns
        $this->initializePatterns();
    }

    /**
     * Sets up the initial patterns and responses
     * In a production environment, these could be loaded from a database
     */
    private function initializePatterns(): void {
        $this->patternGroups[] = new PatternGroup(
            ['hello', 'hi', 'hey', 'greetings'],
            [
                "Hello! How can I help you today?",
                "Hi there! What's on your mind?",
                "Hey! How can I assist you today?"
            ]
        );

        $this->patternGroups[] = new PatternGroup(
            ['how are you', 'how do you do', 'how are things'],
            [
                "I'm doing well, thank you for asking! How about you?",
                "I'm great! How can I assist you today?"
            ]
        );

        $this->patternGroups[] = new PatternGroup(
            ['weather', 'temperature', 'forecast'],
            [
                "I can't check the actual weather, but I can help you find weather information!",
                "Would you like to know about weather forecasts?"
            ]
	);
	$this->patternGroups[] = new PatternGroup(
            ['sad', 'office', 'manager', 'hr', 'boss', 'deadline', 'bad', 'very bad', 'feeling bad', 'not great'],
            [
                "Your boss seems to rude on you but no problem be patient sometimes you have to go through the wrost to get the best",
                "I can feel you man.to be honest senior personalities don't have their own life so they try to distroy others.but I am always with you to heard of your problems",
                "I know its too hard for you to continue this but be patient one day you will your time will come",
                "I am really feeling bad for you. Can I assist you somehow to feel you better?"
            ]
  );
        $this->patternGroups[] = new PatternGroup(
            ['ok', 'thanks', 'great', 'understand me', 'one who', 'you are only one'],
            [
                "Thanks! Can I help you more to make you happy?",
                "I am really happy that you are now feeling little bit great!",
                "Its my honor, BTW can I assist you somehow else"
            ]
        );



    }

    /**
     * Processes user input by cleaning and standardizing it
     */
    private function processInput(string $input): array {
        // Convert to lowercase and trim whitespace
        $input = strtolower(trim($input));
        
        // Remove punctuation and special characters
        $input = preg_replace('/[^\w\s]/', '', $input);
        
        // Split into words and return as array
        return explode(' ', $input);
    }

    /**
     * Finds the best matching pattern group for the processed input
     */
    private function findBestMatch(array $processedInput): ?PatternGroup {
        $bestMatch = null;
        $maxMatchedWords = 0;

        foreach ($this->patternGroups as $patternGroup) {
            foreach ($patternGroup->getPatterns() as $pattern) {
                $patternWords = explode(' ', $pattern);
                $matchedWords = 0;

                // Count matching words
                foreach ($processedInput as $word) {
                    if (strpos($pattern, $word) !== false) {
                        $matchedWords++;
                    }
                }

                // Update best match if this is better
                if ($matchedWords > $maxMatchedWords) {
                    $maxMatchedWords = $matchedWords;
                    $bestMatch = $patternGroup;
                }
            }
        }

        return $bestMatch;
    }

    /**
     * Selects a random response from the available responses
     */
    private function getRandomResponse(array $responses): string {
        $randomIndex = array_rand($responses);
        return $responses[$randomIndex];
    }

    /**
     * Main method to generate a response to user input
     */
    public function generateResponse(string $userInput): string {
        // Process the input
        $processedInput = $this->processInput($userInput);
        
        // Find the best matching pattern
        $match = $this->findBestMatch($processedInput);
        
        // Generate and return response
        if ($match) {
            return $this->getRandomResponse($match->getResponses());
        }
        
        return "I'm not sure I understand. Could you rephrase that?";
    }

    /**
     * Allows adding new pattern groups dynamically
     */
    public function addPatternGroup(array $patterns, array $responses): void {
        $this->patternGroups[] = new PatternGroup($patterns, $responses);
    }
}
