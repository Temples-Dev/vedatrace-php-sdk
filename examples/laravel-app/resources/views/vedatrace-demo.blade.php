<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VedaTrace | Laravel Demo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/vedatrace.css">
</head>
<body>
    <div class="glow-bg"></div>
    
    <main class="container">
        <header>
            <div class="logo">
                <span class="logo-icon">V</span>
                <h1>VedaTrace <span class="badge">Laravel Demo</span></h1>
            </div>
            <p class="subtitle">Experience structured logging with rich metadata, security, and real-time insights.</p>
        </header>

        <section class="grid">
            <!-- Simulator Panel -->
            <div class="card glass">
                <div class="card-header">
                    <h2>Logger Simulator</h2>
                    <p>Trigger logs with different levels and metadata.</p>
                </div>
                
                <div class="form-group">
                    <label>Log Message</label>
                    <input type="text" id="log-message" placeholder="e.g. User subscription renewed" value="User subscription renewed">
                </div>

                <div class="form-group">
                    <label>Log Level</label>
                    <div class="level-selector">
                        <button class="level-btn info active" data-level="info">Info</button>
                        <button class="level-btn warning" data-level="warning">Warning</button>
                        <button class="level-btn error" data-level="error">Error</button>
                        <button class="level-btn critical" data-level="critical">Critical</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Metadata (JSON)</label>
                    <textarea id="log-metadata" rows="6">{
  "user_id": 1234,
  "plan": "premium",
  "amount": 29.99,
  "currency": "USD",
  "secure": {
    "token": "ghp_xxxxxxxxxxxx",
    "password": "secret_password"
  }
}</textarea>
                </div>

                <button id="send-btn" class="primary-btn">
                    <span>Send Log to VedaTrace</span>
                    <div class="btn-glow"></div>
                </button>
                
                <button id="error-btn" class="secondary-btn">
                    <span>Simulate Exception</span>
                </button>
            </div>

            <!-- Feed Panel -->
            <div class="card glass dark">
                <div class="card-header">
                    <div class="flex-between">
                        <h2>Event Feed</h2>
                        <span class="status-indicator">
                            <span class="pulse"></span> Live
                        </span>
                    </div>
                </div>
                
                <div id="log-feed" class="feed">
                    <div class="feed-placeholder">
                        <p>Sent logs will appear here in real-time...</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="info-footer">
            <div class="info-card glass">
                <h3>Structured Metadata</h3>
                <p>Pass associative arrays directly to the Log facade. VedaTrace handles the nesting and serialization automatically.</p>
            </div>
            <div class="info-card glass">
                <h3>Auto Redaction</h3>
                <p>Keys like <code>password</code> and <code>token</code> in the metadata are automatically masked before transit.</p>
            </div>
            <div class="info-card glass">
                <h3>Error Tracking</h3>
                <p>Full stack traces are captured when exceptions are logged, making debugging 10x faster.</p>
            </div>
        </section>
    </main>

    <script>
        // Interactive Logic
        const sendBtn = document.getElementById('send-btn');
        const errorBtn = document.getElementById('error-btn');
        const levelBtns = document.querySelectorAll('.level-btn');
        const feed = document.getElementById('log-feed');
        let selectedLevel = 'info';

        levelBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                levelBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                selectedLevel = btn.dataset.level;
            });
        });

        async function sendLog(url, options = {}) {
            sendBtn.disabled = true;
            try {
                const response = await fetch(url, options);
                const data = await response.json();
                addFeedEntry(data.data || data);
            } catch (err) {
                console.error(err);
                addFeedEntry({ message: 'Error sending log', level: 'error', status: 'failure' });
            }
            sendBtn.disabled = false;
        }

        sendBtn.addEventListener('click', () => {
            const message = document.getElementById('log-message').value;
            const metadataStr = document.getElementById('log-metadata').value;
            let metadata = {};
            try {
                metadata = JSON.parse(metadataStr);
            } catch(e) {
                alert('Invalid JSON in metadata');
                return;
            }

            const formData = new FormData();
            formData.append('level', selectedLevel);
            formData.append('message', message);
            formData.append('metadata', JSON.stringify(metadata));

            sendLog('/api/send-log', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        });

        errorBtn.addEventListener('click', () => {
            sendLog('/api/simulate-error');
        });

        function addFeedEntry(data) {
            const placeholder = feed.querySelector('.feed-placeholder');
            if (placeholder) placeholder.remove();

            const entry = document.createElement('div');
            entry.className = `feed-entry ${data.level || 'info'} fade-in`;
            
            const timestamp = new Date().toLocaleTimeString();
            
            entry.innerHTML = `
                <div class="entry-header">
                    <span class="level-tag">${(data.level || 'info').toUpperCase()}</span>
                    <span class="time">${timestamp}</span>
                </div>
                <div class="entry-content">
                    <p class="msg">${data.message}</p>
                    ${data.metadata ? `<pre><code>${JSON.stringify(data.metadata, null, 2)}</code></pre>` : ''}
                </div>
            `;
            
            feed.prepend(entry);
            
            // Limit entries
            if (feed.children.length > 5) {
                feed.removeChild(feed.lastChild);
            }
        }
    </script>
</body>
</html>
