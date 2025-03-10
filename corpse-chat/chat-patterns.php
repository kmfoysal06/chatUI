<?php
/**
 * Class that represents a pattern-response pair in the chatbot
 * This helps encapsulate the relationship between patterns and their responses
 */
class PatternGroup {
    private array $patterns;
    private array $responses;

    public function __construct(array $patterns, array $responses) {
        $this->patterns = $patterns;
        $this->responses = $responses;
    }

    public function getPatterns(): array {
        return $this->patterns;
    }

    public function getResponses(): array {
        return $this->responses;
    }
}
