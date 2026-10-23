<!DOCTYPE html>
<html>
<head>
    <title>Hostinger Diagnostic Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        .test { margin: 15px 0; padding: 15px; border-left: 4px solid #2196F3; background: #f9f9f9; }
        .success { border-left-color: #4CAF50; background: #f1f8f4; }
        .error { border-left-color: #f44336; background: #fef1f1; }
        .warning { border-left-color: #ff9800; background: #fff8f1; }
        .label { font-weight: bold; color: #555; }
        .value { color: #333; margin-left: 10px; }
        code { background: #eee; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        .section { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Hostinger Diagnostic Tool</h1>
        <p>This tool helps diagnose routing issues on Hostinger.</p>

        <div class="section">
            <h2>1. Server Information</h2>
            
            <div class="test">
                <span class="label">PHP Version:</span>
                <span class="value"><?php echo phpversion(); ?></span>
            </div>

            <div class="test">
                <span class="label">Server Software:</span>
                <span class="value"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></span>
            </div>

            <div class="test">
                <span class="label">Document Root:</span>
                <span class="value"><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></span>
            </div>

            <div class="test">
                <span class="label">Script Filename:</span>
                <span class="value"><?php echo $_SERVER['SCRIPT_FILENAME'] ?? 'Unknown'; ?></span>
            </div>
        </div>

        <div class="section">
            <h2>2. mod_rewrite Check</h2>
            
            <?php
            $mod_rewrite_enabled = false;
            if (function_exists('apache_get_modules')) {
                $modules = apache_get_modules();
                $mod_rewrite_enabled = in_array('mod_rewrite', $modules);
            } else {
                // Alternative check
                $mod_rewrite_enabled = (getenv('HTTP_MOD_REWRITE') == 'On');
            }
            ?>
            
            <div class="test <?php echo $mod_rewrite_enabled ? 'success' : 'warning'; ?>">
                <span class="label">mod_rewrite Status:</span>
                <span class="value">
                    <?php 
                    if ($mod_rewrite_enabled) {
                        echo '✅ Enabled';
                    } else {
                        echo '⚠️ Cannot detect (may still be enabled)';
                    }
                    ?>
                </span>
            </div>
        </div>

        <div class="section">
            <h2>3. File System Checks</h2>
            
            <?php
            $files_to_check = [
                '.htaccess' => '.htaccess',
                'index.php' => 'index.php',
                'AI_buddy Controller' => 'application/controllers/AI_buddy.php',
                'Config' => 'application/config/config.php',
                'Routes' => 'application/config/routes.php',
            ];

            foreach ($files_to_check as $name => $path) {
                $exists = file_exists($path);
                $readable = $exists && is_readable($path);
                ?>
                <div class="test <?php echo ($exists && $readable) ? 'success' : 'error'; ?>">
                    <span class="label"><?php echo $name; ?>:</span>
                    <span class="value">
                        <?php 
                        if ($exists && $readable) {
                            echo '✅ Exists and readable';
                        } elseif ($exists) {
                            echo '⚠️ Exists but not readable';
                        } else {
                            echo '❌ Not found';
                        }
                        ?>
                    </span>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="section">
            <h2>4. CodeIgniter Configuration</h2>
            
            <?php
            if (file_exists('application/config/config.php')) {
                include('application/config/config.php');
                ?>
                <div class="test">
                    <span class="label">Base URL:</span>
                    <span class="value"><code><?php echo $config['base_url'] ?? 'Not set'; ?></code></span>
                </div>

                <div class="test">
                    <span class="label">Index Page:</span>
                    <span class="value"><code><?php echo $config['index_page'] === '' ? '(empty - clean URLs)' : $config['index_page']; ?></code></span>
                </div>

                <div class="test">
                    <span class="label">URI Protocol:</span>
                    <span class="value"><code><?php echo $config['uri_protocol'] ?? 'Not set'; ?></code></span>
                </div>
                <?php
            } else {
                ?>
                <div class="test error">
                    <span class="value">❌ Cannot read config.php</span>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="section">
            <h2>5. .htaccess Content</h2>
            
            <?php
            if (file_exists('.htaccess') && is_readable('.htaccess')) {
                $htaccess_content = file_get_contents('.htaccess');
                ?>
                <div class="test success">
                    <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto;"><?php echo htmlspecialchars($htaccess_content); ?></pre>
                </div>
                <?php
            } else {
                ?>
                <div class="test error">
                    <span class="value">❌ Cannot read .htaccess file</span>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="section">
            <h2>6. URL Testing</h2>
            
            <div class="test">
                <p><strong>Test these URLs:</strong></p>
                <ol>
                    <li>With index.php: <code><?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/index.php/ai_buddy/generate_quiz</code></li>
                    <li>Without index.php: <code><?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/ai_buddy/generate_quiz</code></li>
                </ol>
            </div>
        </div>

        <div class="section">
            <h2>7. Recommendations</h2>
            
            <?php
            $recommendations = [];
            
            if (!$mod_rewrite_enabled && !function_exists('apache_get_modules')) {
                $recommendations[] = '⚠️ Cannot detect mod_rewrite. Contact Hostinger support to verify it\'s enabled.';
            }
            
            if (isset($config['index_page']) && $config['index_page'] !== '') {
                $recommendations[] = '⚠️ index_page is not empty. Set it to empty string for clean URLs.';
            }
            
            if (isset($config['base_url']) && (empty($config['base_url']) || $config['base_url'] === 'http://localhost:8000/')) {
                $recommendations[] = '❌ base_url is not set correctly. Update it to your actual domain.';
            }
            
            if (!file_exists('.htaccess')) {
                $recommendations[] = '❌ .htaccess file is missing. Upload it to the root directory.';
            }
            
            if (empty($recommendations)) {
                ?>
                <div class="test success">
                    <span class="value">✅ Configuration looks good! If you still have issues, try the different .htaccess versions in HOSTINGER_HTACCESS_VERSIONS.md</span>
                </div>
                <?php
            } else {
                foreach ($recommendations as $rec) {
                    ?>
                    <div class="test warning">
                        <span class="value"><?php echo $rec; ?></span>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <div class="section">
            <h2>8. Next Steps</h2>
            <div class="test">
                <ol>
                    <li>Fix any issues shown above</li>
                    <li>Test the URLs in section 6</li>
                    <li>If URL #1 works but #2 doesn't, try different .htaccess versions from <code>HOSTINGER_HTACCESS_VERSIONS.md</code></li>
                    <li>If URL #1 doesn't work, check <code>application/logs/</code> for errors</li>
                    <li>Contact Hostinger support if issues persist</li>
                </ol>
            </div>
        </div>

        <div style="margin-top: 30px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
            <strong>⚠️ Security Note:</strong> Delete this diagnostic.php file after troubleshooting!
        </div>
    </div>
</body>
</html>
