<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['roomId'])) {
  header("Location: dashboard.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>DevCollab - Code & Chat</title>
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
      scrollbar-width: none;
      -ms-overflow-style: none;
      overflow: auto;
    }

    html::-webkit-scrollbar,
    body::-webkit-scrollbar {
      display: none;
    }

    body {
      font-family: Arial, sans-serif;
      background: #181c1f;
      color: #d4d4d4;
      min-height: 100vh;
      height: 100vh;
      scrollbar-width: none;
      -ms-overflow-style: none;
      overflow: auto;
    }


    #editor {
      width: 100%;
      height: 60vh;
      font-family: 'Fira Mono', 'Consolas', 'Menlo', 'Monaco', monospace;
      font-size: 16px;
      padding: 16px;
      border: 1.5px solid #2d2d2d;
      background: #1e1e1e;
      color: #d4d4d4;
      border-radius: 6px;
      box-shadow: 0 2px 12px rgba(30, 30, 30, 0.15);
      outline: none;
      transition: border 0.2s, box-shadow 0.2s;
      resize: none;
    }

    #editor:focus {
      border: 2px solid #007acc;
      box-shadow: 0 0 0 2px #007acc44;
      background: #23272e;
    }


    #chat {
      width: 100%;
      height: 22vh;
      overflow-y: auto;
      background: linear-gradient(135deg, #f7fafc 0%, #e3e9f6 100%);
      border-top: 1px solid #e0e7ef;
      border-bottom: 1px solid #e0e7ef;
      padding: 18px 18px 10px 18px;
      box-sizing: border-box;
      box-shadow: 0 2px 12px rgba(79, 140, 255, 0.07);
      border-radius: 10px 10px 0 0;
      font-size: 15px;
      transition: background 0.3s;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    #chat div {
      width: 100%;
      background: #fff;
      border-radius: 7px;
      padding: 8px 14px;
      margin-bottom: 2px;
      box-shadow: 0 1px 4px rgba(79, 140, 255, 0.08);
      color: #2d3a4a;
      word-break: break-word;
      font-size: 15px;
      transition: background 0.2s, color 0.2s;
      border-left: 6px solid #4f8cff;
      max-width: 100%;
      box-sizing: border-box;
    }

    #chat div:nth-child(even) {
      background: linear-gradient(90deg, #e0c3fc 0%, #8ec5fc 100%);
      color: #2356c7;
      align-self: flex-end;
      border-left: 6px solid #ffb86c;
    }

    #chat div:nth-child(3n) {
      background: linear-gradient(90deg, #fbc2eb 0%, #a6c1ee 100%);
      color: #a83279;
      border-left: 6px solid #f67280;
    }

    #chat div:nth-child(4n) {
      background: linear-gradient(90deg, #f9f586 0%, #fbc2eb 100%);
      color: #b8860b;
      border-left: 6px solid #ffd700;
    }

    #chat::-webkit-scrollbar {
      width: 8px;
      background: #e3e9f6;
      border-radius: 8px;
    }

    #chat::-webkit-scrollbar-thumb {
      background: #b6c8e6;
      border-radius: 8px;
    }

    #message-box {
      display: flex;
      align-items: center;
      border-top: 1px solid #eee;
    }

    #message {
      flex: 1;
      padding: 12px;
      font-size: 16px;
      border: none;
      background: #f9f9f9;
    }

    #recipient {
      width: 160px;
      padding: 10px;
      font-size: 14px;
      border: none;
      background: #f1f1f1;
    }
  </style>
</head>

<body>
  <div style="display: flex; height: 100vh; gap: 0;">

    <div
      style="flex: 1.2; display: flex; flex-direction: column; padding: 40px 36px 36px 40px; min-width: 0; background: #f5f7fa; border-right: 2.5px solid #e0e7ef; box-sizing: border-box;">
      <span
        style="background: linear-gradient(90deg, #ffd700 0%, #ffb86c 100%); color: #23272e; font-weight: bold; padding: 4px 14px; border-radius: 6px; box-shadow: 0 1px 4px rgba(255,215,0,0.12); font-size: 16px; letter-spacing: 0.5px;">Editor</span>
      <textarea id="editor" placeholder="Start coding..."></textarea>
      <div style="display: flex; flex-direction: column;">
        <div>
          <div style="display: flex; align-items: center; gap: 12px; margin: 18px 0 10px 0; justify-content: flex-end;">
            <select
              style="padding: 10px 16px; font-size: 15px; border-radius: 6px; border: 1px solid #d1d5db; background: #f7fafc; color: #333; outline: none; transition: border 0.2s;"
              id="language">
              <option value="c">C</option>
              <option value="cpp">C++</option>
              <option value="csharp">C#</option>
              <option value="java">Java</option>
              <option value="python">Python</option>
              <option value="javascript">JavaScript</option>
              <option value="php">PHP</option>
              <option value="ruby">Ruby</option>
              <option value="go">Go</option>
              <option value="rust">Rust</option>
              <option value="typescript">TypeScript</option>
              <option value="swift">Swift</option>
              <option value="kotlin">Kotlin</option>
              <option value="html">HTML</option>
              <option value="css">CSS</option>
              <option value="sql">SQL</option>
              <option value="bash">Bash</option>
              <option value="r">R</option>
            </select>
            <button id="compileBtn" onclick="compileCode()"
              style="padding: 10px 22px; font-size: 15px; border-radius: 6px; border: none; background: linear-gradient(90deg, #4f8cff 0%, #2356c7 100%); color: #fff; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px rgba(79,140,255,0.18); transition: background 0.2s; outline: 3px solid #ffd700; outline-offset: 2px;margin:20px">
              <span style="vertical-align: middle;">&#9881;</span> <span id="compileBtnText">Compile code</span>
            </button>
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#chatBotModal"
              style="margin-left:10px;">
              Chat Bot
            </button>
          </div>
          <span
            style="background: linear-gradient(90deg, #ffd700 0%, #ffb86c 100%); color: #23272e; font-weight: bold; padding: 4px 14px; border-radius: 6px; box-shadow: 0 1px 4px rgba(255,215,0,0.12); font-size: 16px; letter-spacing: 0.5px;">Output</span>
          <div id="result" style="
            background: #181c1f;
            color: #d4d4d4;
            font-family: 'Fira Mono', 'Consolas', 'Menlo', 'Monaco', monospace;
            font-size: 15px;
            border-radius: 7px;
            border: 1.5px solid #23272e;
            box-shadow: 0 4px 18px rgba(30,30,30,0.22);
            padding: 20px 24px 20px 24px;
            margin: 0 0 22px 0;
            min-height: 120px;
            max-height: 340px;
            overflow-y: auto;
            white-space: pre-wrap;
            line-height: 1.7;
            position: relative;
          ">
            <div style="display: flex; justify-content: flex-start;">
              <span
                style="color: #6a9955; font-size: 15px; font-weight: 600; letter-spacing: 0.5px; display: inline-block; margin-right: 8px;">user@devcollab:~$</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      style="flex: 0.8; display: flex; flex-direction: column; border-left: 2px solid #e0e7ef; background: #f7fafc; min-width: 340px; max-width: 480px; height: 100vh; box-sizing: border-box; padding: 0 0 0 0;">
      <div style="flex: 1; display: flex; flex-direction: column; height: 100%;">
        <div id="chat" style="flex: 1 1 auto;"></div>
        <div id="message-box" style="margin-bottom:10px">
          <input type="text" id="message" placeholder="Type a message" style="
            flex: 1;
            padding: 12px 16px;
            font-size: 16px;
            border: 2.5px solid #ffd700;
            border-radius: 8px;
            background: #fffbe6;
            margin-right: 10px;
            box-shadow: 0 2px 12px rgba(255,215,0,0.18), 0 1px 4px rgba(79,140,255,0.06);
            outline: 3px solid #ffd700;
            outline-offset: 2px;
            transition: box-shadow 0.2s, background 0.2s, border 0.2s;
            "
            onfocus="this.style.background='#fffde7';this.style.boxShadow='0 4px 16px rgba(255,215,0,0.22)';this.style.border='2.5px solid #ffb300';"
            onblur="this.style.background='#fffbe6';this.style.boxShadow='0 2px 12px rgba(255,215,0,0.18), 0 1px 4px rgba(79,140,255,0.06)';this.style.border='2.5px solid #ffd700';">
          <select id="recipient" style="
            padding: 8px 10px;
            font-size: 15px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background: linear-gradient(90deg, #f7fafc 0%, #e3e9f6 100%);
            color: #2356c7;
            outline: none;
            margin: 0 8px 0 0;
            box-shadow: 0 1px 4px rgba(79,140,255,0.06);
            transition: border 0.2s, box-shadow 0.2s;
            ">
            <option value="0">All</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <div class="modal fade" id="chatBotModal" tabindex="-1" aria-labelledby="chatBotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="min-height:400px;">
        <div class="modal-header">
          <h5 class="modal-title" id="chatBotModalLabel">DevCollab Chat Bot</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="overflow-y:auto;max-height:350px;">
          <div id="chatbot-messages" style="min-height:200px;max-height:250px;overflow-y:auto;padding-bottom:10px;">
          </div>
          <div class="input-group mt-2">
            <input type="text" id="chatbot-input" class="form-control" placeholder="Ask the bot..." autocomplete="off">
            <button class="btn btn-primary" id="chatbot-send" type="button">Send</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function compileCode() {
      const code = document.getElementById('editor').value;
      const language = document.getElementById('language').value;
      document.getElementById('compileBtnText').textContent = 'Compiling...';
      const languageMap = {
        'c': { lang: 'c', version: '10.2.0' },
        'cpp': { lang: 'cpp', version: '10.2.0' },
        'csharp.net': { lang: 'csharp', version: '9.0' },
        'java': { lang: 'java', version: '15.0.2' },
        'python': { lang: 'python', version: '3.10.0' },
        'javascript': { lang: 'javascript', version: '18.15.0' },
        'php': { lang: 'php', version: '8.2.3' },
        'ruby': { lang: 'ruby', version: '3.2.0' },
        'go': { lang: 'go', version: '1.20.2' },
        'rust': { lang: 'rust', version: '1.68.2' },
        'typescript': { lang: 'typescript', version: '5.0.3' },
        'swift': { lang: 'swift', version: '5.7.3' },
        'kotlin': { lang: 'kotlin', version: '1.8.20' },
        'html': { lang: 'html', version: '5.0.0' },
        'css': { lang: 'css', version: '1.0.0' },
        'sql': { lang: 'mysql', version: '8.0.32' },
        'bash': { lang: 'bash', version: '5.1.16' },
        'r': { lang: 'r', version: '4.2.2' }
      };
      const map = languageMap[language] || { lang: language, version: '*' };
      fetch('https://emkc.org/api/v2/piston/execute', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          language: map.lang,
          version: map.version,
          files: [{ name: `main.${map.lang}`, content: code }]
        })
      })
        .then(res => {
          if (!res.ok) throw new Error('Server error: ' + res.status);
          return res.json();
        })
        .then(result => {
          let output = '';
          if (result.run && result.run.stdout) {
            output += `<span style="color: #6a9955; font-weight: 600;">user@devcollab:~$</span> <pre style="display:inline;background:none;border:none;color:#d4d4d4;font-family:inherit;font-size:inherit;padding:0;margin:0;">${result.run.stdout}</pre>`;
          }
          if (result.run && result.run.stderr) {
            output += `<span style="color: #f67280; font-weight: 600;">user@devcollab:~$</span> <pre style="display:inline;background:none;border:none;color:#ffb86c;font-family:inherit;font-size:inherit;padding:0;margin:0;">${result.run.stderr}</pre>`;
          }
          if (result.compile && result.compile.stdout) {
            output += `<span style="color: #ffd700; font-weight: 600;">compiler@devcollab:~$</span> <pre style="display:inline;background:none;border:none;color:#ffd700;font-family:inherit;font-size:inherit;padding:0;margin:0;">${result.compile.stdout}</pre>`;
          }
          if (result.compile && result.compile.stderr) {
            output += `<span style="color: #f67280; font-weight: 600;">compiler@devcollab:~$</span> <pre style="display:inline;background:none;border:none;color:#f67280;font-family:inherit;font-size:inherit;padding:0;margin:0;">${result.compile.stderr}</pre>`;
          }
          if (!output) {
            output = '<pre style="color:#6a9955;">No output.</pre>';
          }
          document.getElementById('result').innerHTML = output;
          document.getElementById('compileBtnText').textContent = 'Compile code';
        })
        .catch(err => {
          console.error('Error compiling code:', err);
          document.getElementById('result').innerHTML = `<pre style="color:#f67280;">Error: ${err.message}</pre>`;
          document.getElementById('compileBtnText').textContent = 'Compile code';
        });
    }
    const ws = new WebSocket('ws://localhost:8080');
    const editor = document.getElementById('editor');
    const message = document.getElementById('message');
    const chat = document.getElementById('chat');
    const recipient = document.getElementById('recipient');
    let suppressNextUpdate = false;
    const userId = <?= json_encode($_SESSION['id']) ?>;
    const roomId = <?= json_encode($_SESSION['roomId']) ?>;

    ws.onopen = function () {
      ws.send(JSON.stringify({ type: 'init', user_id: userId, room_id: roomId }));
    };

    window.onload = function () {
      fetch('load_code.php')
        .then(res => res.text())
        .then(data => editor.value = data);

      fetch('get_room_members.php')
        .then(res => res.json())
        .then(users => {
          users.forEach(user => {
            if (user.id != userId) {
              const opt = document.createElement('option');
              opt.value = user.id;
              opt.textContent = user.username;
              recipient.appendChild(opt);
            }
          });
        });
    };

    setTimeout(() => {
      setInterval(() => {
        fetch('save_code.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'code=' + encodeURIComponent(editor.value)
        });
      }, 5000);
    }, 3000);
    let chatWasAtBottom = true;

    setInterval(() => {
      chatWasAtBottom = chat.scrollHeight - chat.scrollTop - chat.clientHeight < 10;

      fetch('load_chat.php')
        .then(res => res.text())
        .then(html => {
          chat.innerHTML = html;
          if (chatWasAtBottom) {
            chat.scrollTop = chat.scrollHeight;
          }
        });
    }, 200);
    editor.addEventListener('input', () => {
      if (!suppressNextUpdate) {
        ws.send(JSON.stringify({ type: 'code', content: editor.value }));
      }
    });

    message.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        const msgText = message.value.trim();
        const targetId = recipient.value;
        if (msgText) {

          message.value = '';
          fetch('save_chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'message=' + encodeURIComponent(msgText) +
              '&fromId=' + encodeURIComponent(userId) +
              '&toId=' + encodeURIComponent(targetId) +
              '&roomId=' + encodeURIComponent(roomId)
          });
          console.log(msgText, userId, targetId, roomId);
        }
      }
    });

    ws.onmessage = (event) => {
      const data = JSON.parse(event.data);
      if (data.type === 'code') {
        suppressNextUpdate = true;
        editor.value = data.content;
        suppressNextUpdate = false;
      }
      
    };

    function appendChat(msg) {
      const div = document.createElement('div');
      div.textContent = msg;
      chat.appendChild(div);
      chat.scrollTop = chat.scrollHeight;
    }

    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotMessages = document.getElementById('chatbot-messages');

    function appendBotMessage(msg, from = 'bot') {
      const div = document.createElement('div');
      div.className = from === 'user' ? 'text-end mb-2' : 'text-start mb-2';

      let formattedMsg = msg;
      if (msg.length > 120 || msg.includes('\n')) {
        formattedMsg = `<pre style="display:inline;white-space:pre-wrap;word-break:break-word;background:none;border:none;padding:0;margin:0;">${escapeHtml(msg)}</pre>`;
      } else {
        formattedMsg = escapeHtml(msg);
      }

      div.innerHTML = `<span class="badge ${from === 'user' ? 'bg-primary' : 'bg-secondary'}" style="max-width:90%;overflow-wrap:break-word;white-space:pre-line;word-break:break-word;">${formattedMsg}</span>`;
      chatbotMessages.appendChild(div);
      chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function escapeHtml(text) {
      return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }

    async function sendBotMessage() {
      const msg = chatbotInput.value.trim();
      if (!msg) return;

      appendBotMessage(msg, 'user');
      chatbotInput.value = '';
      appendBotMessage("Thinking...", 'bot');

      try {
        const response = await fetch(
          'chatbot_proxy.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            message: msg
          })
        }
        );



        if (!response.ok) throw new Error('API error: ' + response.status);

        const data = await response.json();
        chatbotMessages.removeChild(chatbotMessages.lastChild);

        let reply = "Sorry, no response.";
        if (data.reply) {
          reply = data.reply.trim();
        } else if (data.error) {
          reply = "Error: " + data.error;
        }

        appendBotMessage(reply, 'bot');

      } catch (e) {
        chatbotMessages.removeChild(chatbotMessages.lastChild);
        appendBotMessage("Error contacting bot. " + (e.message || ''), 'bot');
      }
    }


    chatbotSend.onclick = sendBotMessage;
    chatbotInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') sendBotMessage();
    });

    document.getElementById('chatBotModal').addEventListener('show.bs.modal', function () {
      chatbotMessages.innerHTML = '';
      appendBotMessage("Hi! I'm your DevCollab bot. How can I help you?");
    });




    let typingTimeout;
    const typingIndicator = document.createElement('div');
    typingIndicator.id = 'typing-indicator';
    typingIndicator.style.cssText = 'color:#2356c7;font-size:13px;margin:6px 0 0 2px;min-height:18px;';
    editor.parentNode.insertBefore(typingIndicator, editor.nextSibling);

    editor.addEventListener('input', () => {
      if (!suppressNextUpdate) {
      ws.send(JSON.stringify({ type: 'code', content: editor.value }));
      ws.send(JSON.stringify({ type: 'typing_in_code', user_id: userId, room_id: roomId }));
      }
    });

    ws.addEventListener('message', (event) => {
      const data = JSON.parse(event.data);
      if (data.type === 'user_typing_in_code' && data.user_id !== userId) {
      fetch('get_user_info.php?user_id=' + data.user_id)
        .then(res => res.json())
        .then(userInfo => {
          console.log('User typing:', userInfo);
          typingIndicator.textContent = (userInfo.username ? userInfo.username : 'Someone') + ' is typing...';
        });
      clearTimeout(typingTimeout);
      typingTimeout = setTimeout(() => {
        typingIndicator.textContent = '';
      }, 1500);
      }
    });

  </script>
</body>

</html>
