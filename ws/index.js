import { WebSocketServer } from 'ws';
import { v4 as uuid } from 'uuid';

const PORT = process.env.PORT || 7071;
const wss = new WebSocketServer({ port: PORT });

/** sessionId => Set(ws) */
const rooms = new Map();

function joinRoom(ws, sessionId) {
  if (!rooms.has(sessionId)) rooms.set(sessionId, new Set());
  rooms.get(sessionId).add(ws);
  ws._sessionId = sessionId;
}

function leaveRoom(ws) {
  const sid = ws._sessionId;
  if (!sid) return;
  const set = rooms.get(sid);
  if (set) {
    set.delete(ws);
    if (set.size === 0) rooms.delete(sid);
  }
  ws._sessionId = undefined;
}

function broadcast(sessionId, payload) {
  const set = rooms.get(sessionId);
  if (!set) return;
  const data = JSON.stringify(payload);
  for (const client of set) {
    if (client.readyState === client.OPEN) {
      client.send(data);
    }
  }
}

wss.on('connection', (ws) => {
  ws.id = uuid();
  ws.isAlive = true;

  ws.on('pong', () => { ws.isAlive = true; });

  ws.on('message', (raw) => {
    let msg;
    try { msg = JSON.parse(raw.toString()); } catch (_) { return; }
    const { type } = msg || {};
    if (type === 'join') {
      const { sessionId, user } = msg;
      joinRoom(ws, sessionId);
      ws._user = user || `user-${ws.id.slice(0, 6)}`;
      ws.send(JSON.stringify({ type: 'joined', sessionId, user: ws._user }));
      broadcast(sessionId, { type: 'presence', sessionId, users: Array.from(rooms.get(sessionId) || []).map(c => c._user).filter(Boolean) });
    } else if (type === 'vote') {
      const { sessionId, user, value } = msg;
      broadcast(sessionId, { type: 'vote', sessionId, user, value });
    } else if (type === 'finalize') {
      const { sessionId } = msg;
      broadcast(sessionId, { type: 'finalized', sessionId });
    } else if (type === 'ping') {
      ws.send(JSON.stringify({ type: 'pong' }));
    }
  });

  ws.on('close', () => {
    const sid = ws._sessionId;
    leaveRoom(ws);
    if (sid) broadcast(sid, { type: 'presence', sessionId: sid, users: Array.from(rooms.get(sid) || []).map(c => c._user).filter(Boolean) });
  });
});

// Heartbeat
const interval = setInterval(() => {
  for (const ws of wss.clients) {
    if (ws.isAlive === false) return ws.terminate();
    ws.isAlive = false;
    ws.ping();
  }
}, 30000);

wss.on('close', () => {
  clearInterval(interval);
});

console.log(`WS server listening on ws://localhost:${PORT}`);
