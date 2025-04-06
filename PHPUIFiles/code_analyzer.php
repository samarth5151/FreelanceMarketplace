<?php

class CodeAnalyzer {
    private $filePath;
    private $language;
    private $code;
    private $issues = [];
    private $weights = [
        'syntax' => 30,
        'complexity' => 15,
        'function_length' => 10,
        'comment_density' => 10,
        'unused_variables' => 5,
        'indentation' => 10,
        'magic_numbers' => 5,
        'duplication' => 15
    ];
    
    public function __construct($filePath, $language) {
        $this->filePath = $filePath;
        $this->language = strtolower($language);
        
        if (!file_exists($filePath)) {
            throw new Exception("File not found: $filePath");
        }
        
        $this->code = file_get_contents($filePath);
    }
    
    public function analyze() {
        $this->analyzeSyntax();
        $this->analyzeComplexity();
        $this->analyzeFunctionLength();
        $this->analyzeCommentDensity();
        $this->analyzeUnusedVariables();
        $this->analyzeIndentation();
        $this->analyzeMagicNumbers();
        $this->analyzeCodeDuplication();
        
        return [
            'rating' => $this->calculateRating(),
            'issues' => $this->issues,
            'file' => basename($this->filePath)
        ];
    }
    
    private function analyzeSyntax() {
        if ($this->language === 'php') {
            $output = shell_exec('php -l ' . escapeshellarg($this->filePath) . ' 2>&1');
            if (strpos($output, 'No syntax errors detected') === false) {
                $this->issues[] = [
                    'type' => 'syntax',
                    'message' => 'Syntax Error: ' . trim($output)
                ];
            }
        } elseif ($this->language === 'python') {
            // For Python syntax checking in PHP environment
            $tempFile = tempnam(sys_get_temp_dir(), 'python_');
            file_put_contents($tempFile, $this->code);
            $output = shell_exec('python -m py_compile ' . escapeshellarg($tempFile) . ' 2>&1');
            unlink($tempFile);
            
            if ($output !== null) {
                $this->issues[] = [
                    'type' => 'syntax',
                    'message' => 'Syntax Error: ' . trim($output)
                ];
            }
        } else {
            throw new Exception("Unsupported language: {$this->language}");
        }
    }
    
    private function analyzeComplexity() {
        $complexity = 0;
        $keywords = ['if', 'elseif', 'for', 'foreach', 'while', 'switch', 'case'];
        
        foreach ($keywords as $keyword) {
            $complexity += substr_count($this->code, $keyword);
        }
        
        if ($complexity > 10) {
            $this->issues[] = [
                'type' => 'complexity',
                'message' => "High code complexity: $complexity conditionals found."
            ];
        }
    }
    
    private function analyzeFunctionLength() {
        $lines = explode("\n", $this->code);
        $functionLengths = [];
        $insideFunction = false;
        $functionStart = 0;
        
        foreach ($lines as $i => $line) {
            $trimmedLine = trim($line);
            
            // Check for function start (PHP and Python)
            if (!$insideFunction && preg_match('/^\s*(function\s+\w+|def\s+\w+|(public|private|protected)\s+function\s+\w+)/', $trimmedLine)) {
                if ($insideFunction) {
                    $functionLengths[] = $i - $functionStart;
                }
                $insideFunction = true;
                $functionStart = $i;
            } 
            // Check for function end
            elseif ($insideFunction && (strpos($trimmedLine, '}') !== false || strpos($trimmedLine, 'return') !== false)) {
                $functionLengths[] = $i - $functionStart;
                $insideFunction = false;
            }
        }
        
        $longFunctions = array_filter($functionLengths, function($length) {
            return $length > 50;
        });
        
        if (!empty($longFunctions)) {
            $this->issues[] = [
                'type' => 'function_length',
                'message' => count($longFunctions) . " functions exceeding 50 lines."
            ];
        }
    }
    
    private function analyzeCommentDensity() {
        $lines = explode("\n", $this->code);
        $commentLines = 0;
        $codeLines = 0;
        
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            
            if (empty($trimmedLine)) {
                continue;
            }
            
            if (strpos($trimmedLine, '#') === 0 || 
                strpos($trimmedLine, '//') === 0 || 
                strpos($trimmedLine, '/*') === 0 || 
                strpos($trimmedLine, '*') === 0 || 
                strpos($trimmedLine, '*/') === 0) {
                $commentLines++;
            } else {
                $codeLines++;
            }
        }
        
        $totalLines = $commentLines + $codeLines;
        $density = $totalLines > 0 ? $commentLines / $totalLines : 0;
        
        if ($density < 0.1) {
            $this->issues[] = [
                'type' => 'comment_density',
                'message' => "Low comment density: " . round($density * 100, 1) . "%."
            ];
        }
    }
    
    private function analyzeUnusedVariables() {
        $lines = explode("\n", $this->code);
        $variables = [];
        $usedVars = [];
        
        // Find variable assignments
        foreach ($lines as $line) {
            if (preg_match_all('/\$(?<var>\w+)\s*=/', $line, $matches)) {
                foreach ($matches['var'] as $var) {
                    $variables[$var] = true;
                }
            }
        }
        
        // Find variable usage (excluding assignments)
        foreach ($lines as $line) {
            if (preg_match_all('/\$(?<var>\w+)/', $line, $matches)) {
                foreach ($matches['var'] as $var) {
                    if (isset($variables[$var]) && strpos($line, '$' . $var . '=') === false) {
                        $usedVars[$var] = true;
                    }
                }
            }
        }
        
        $unusedVars = array_diff_key($variables, $usedVars);
        
        if (!empty($unusedVars)) {
            $this->issues[] = [
                'type' => 'unused_variables',
                'message' => "Unused variables: " . implode(', ', array_keys($unusedVars))
            ];
        }
    }
    
    private function analyzeIndentation() {
        $lines = explode("\n", $this->code);
        $indentationIssues = [];
        
        foreach ($lines as $i => $line) {
            if (strlen($line) > 0 && $line[0] === ' ') {
                $spaces = strlen($line) - strlen(ltrim($line, ' '));
                if ($spaces % 4 !== 0) {
                    $indentationIssues[] = $i + 1;
                }
            }
        }
        
        if (!empty($indentationIssues)) {
            $this->issues[] = [
                'type' => 'indentation',
                'message' => "Inconsistent indentation on " . count($indentationIssues) . " lines."
            ];
        }
    }
    
    private function analyzeMagicNumbers() {
        $lines = explode("\n", $this->code);
        $magicNumbers = [];
        
        foreach ($lines as $line) {
            if (preg_match_all('/\b\d+(\.\d+)?\b/', $line, $matches)) {
                foreach ($matches[0] as $number) {
                    $num = is_numeric($number) ? floatval($number) : null;
                    if ($num !== null && abs($num) != 0 && abs($num) != 1 && 
                        !(abs($num) < 2 && strpos($number, '.') !== false)) {
                        $magicNumbers[$number] = true;
                    }
                }
            }
        }
        
        if (!empty($magicNumbers)) {
            $this->issues[] = [
                'type' => 'magic_numbers',
                'message' => "Magic numbers: " . implode(', ', array_keys($magicNumbers))
            ];
        }
    }
    
    private function analyzeCodeDuplication() {
        $lines = array_filter(array_map('trim', explode("\n", $this->code)), function($line) {
            return !empty($line);
        });
        
        $duplicates = count($lines) - count(array_unique($lines));
        
        if ($duplicates > 0) {
            $this->issues[] = [
                'type' => 'duplication',
                'message' => "$duplicates duplicated lines."
            ];
        }
    }
    
    private function calculateRating() {
        $hasSyntaxErrors = false;
        $complexityScore = 1;
        $functionLengthScore = 1;
        $commentDensityScore = 1;
        $unusedVarsScore = 1;
        $indentationScore = 1;
        $magicNumbersScore = 1;
        $duplicationScore = 1;
        
        // Check for syntax errors
        foreach ($this->issues as $issue) {
            if ($issue['type'] === 'syntax') {
                $hasSyntaxErrors = true;
                break;
            }
        }
        
        // Check other issues
        foreach ($this->issues as $issue) {
            switch ($issue['type']) {
                case 'complexity':
                    $complexityScore = 0.5;
                    break;
                case 'function_length':
                    $functionLengthScore = 0.5;
                    break;
                case 'comment_density':
                    $commentDensityScore = 0.5;
                    break;
                case 'unused_variables':
                    $unusedVarsScore = 0.5;
                    break;
                case 'indentation':
                    $indentationScore = 0.5;
                    break;
                case 'magic_numbers':
                    $magicNumbersScore = 0.5;
                    break;
                case 'duplication':
                    $duplicationScore = 0.5;
                    break;
            }
        }
        
        // Normalize weights
        $totalWeight = array_sum($this->weights);
        $normalizedWeights = array_map(function($weight) use ($totalWeight) {
            return ($weight / $totalWeight) * 100;
        }, $this->weights);
        
        // Calculate rating
        $rating = 
            ($hasSyntaxErrors ? 0.5 : 1) * $normalizedWeights['syntax'] +
            $complexityScore * $normalizedWeights['complexity'] +
            $functionLengthScore * $normalizedWeights['function_length'] +
            $commentDensityScore * $normalizedWeights['comment_density'] +
            $unusedVarsScore * $normalizedWeights['unused_variables'] +
            $indentationScore * $normalizedWeights['indentation'] +
            $magicNumbersScore * $normalizedWeights['magic_numbers'] +
            $duplicationScore * $normalizedWeights['duplication'];
        
        return round(($rating / 100) * 5, 2);
    }
}

// Main execution
if (PHP_SAPI === 'cli') {
    if (count($argv) !== 3) {
        echo json_encode(['error' => 'Usage: php code_analyzer.php <file_path> <language>']);
        exit(1);
    }
    
    try {
        $analyzer = new CodeAnalyzer($argv[1], $argv[2]);
        $result = $analyzer->analyze();
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit(1);
    }
}