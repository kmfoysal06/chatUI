<?php

class FuzzyMatcher {
    // Dictionary of correct words
    private array $dictionary;
    
    // Threshold for similarity (0-1, where 1 means exact match)
    private float $threshold;
    
    public function __construct(array $dictionary, float $threshold = 0.75) {
        $this->dictionary = $dictionary;
        $this->threshold = $threshold;
    }
    
    /**
     * Finds the best matching word using multiple algorithms
     */
    public function findBestMatch(string $word): ?string {
        $word = strtolower(trim($word));
        
        // If word is in dictionary, return it immediately
        if (in_array($word, $this->dictionary)) {
            return $word;
        }
        
        $bestMatch = null;
        $highestSimilarity = 0;
        
        foreach ($this->dictionary as $dictWord) {
            // Calculate similarity using multiple methods
            $levenshteinSim = $this->levenshteinSimilarity($word, $dictWord);
            $soundexSim = $this->soundexSimilarity($word, $dictWord);
            $metaphoneSim = $this->metaphoneSimilarity($word, $dictWord);
            
            // Take the highest similarity score
            $similarity = max($levenshteinSim, $soundexSim, $metaphoneSim);
            
            // Update best match if this is better
            if ($similarity > $highestSimilarity && $similarity >= $this->threshold) {
                $highestSimilarity = $similarity;
                $bestMatch = $dictWord;
            }
        }
        
        return $bestMatch;
    }
    
    /**
     * Calculates similarity based on Levenshtein distance
     * Returns a value between 0 and 1, where 1 means exact match
     */
    private function levenshteinSimilarity(string $word1, string $word2): float {
        $maxLength = max(strlen($word1), strlen($word2));
        if ($maxLength === 0) return 1.0;
        
        $distance = levenshtein($word1, $word2);
        return 1 - ($distance / $maxLength);
    }
    
    /**
     * Calculates similarity based on Soundex
     * Returns 1 if Soundex codes match, 0 otherwise
     */
    private function soundexSimilarity(string $word1, string $word2): float {
        return soundex($word1) === soundex($word2) ? 1.0 : 0.0;
    }
    
    /**
     * Calculates similarity based on Metaphone
     * Returns 1 if Metaphone codes match, 0 otherwise
     */
    private function metaphoneSimilarity(string $word1, string $word2): float {
        return metaphone($word1) === metaphone($word2) ? 1.0 : 0.0;
    }
    
    /**
     * Processes a full sentence, correcting each word
     */
    public function correctSentence(string $sentence): string {
        $words = explode(' ', strtolower(trim($sentence)));
        $correctedWords = [];
        
        foreach ($words as $word) {
            $corrected = $this->findBestMatch($word) ?? $word;
            $correctedWords[] = $corrected;
        }
        
        return implode(' ', $correctedWords);
    }
}

// Example dictionary and usage
$dictionary = [
    'hello', 'weather', 'forecast', 'temperature',
    'efficiency', 'mechanism', 'behind', 'work',
    'grammar', 'spelling', 'correct', 'what'
];

try {
    // Create fuzzy matcher with dictionary
    $fuzzyMatcher = new FuzzyMatcher($dictionary);
    
    // Test individual words
    $tests = [
        'effeciency',  // Misspelled "efficiency"
        'grammer',     // Misspelled "grammar"
        'behimd',      // Misspelled "behind"
        'speling'      // Misspelled "spelling"
    ];
    
//    foreach ($tests as $test) {
//        $correction = $fuzzyMatcher->findBestMatch($test);
//        echo "Original: $test, Corrected: $correction\n";
//    }
    
    // Test full sentence correction
//    $sentence = "waht macanism behimd them work";
//    $corrected = $fuzzyMatcher->correctSentence($sentence);
////	echo "\nOriginal sentence: $sentence\n";
//	echo "<br>";
//    echo "Corrected sentence: $corrected\n";
    
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>
