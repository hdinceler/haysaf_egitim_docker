import express from "express";
import http from "http";
import { WebSocketServer } from "ws";
import mqtt from "mqtt";

const app = express();
app.use(express.json());

app.get("/health", (req, res) => {
  res.json({ ok: true, env: "dev" });
});

/* HTTP + WS */
const server = http.createServer(app);
const wss = new WebSocketServer({ server });

wss.on("connection", ws => {
  ws.send("WS connected");
});

/* MQTT */
const mqttClient = mqtt.connect("mqtt://test.mosquitto.org");

mqttClient.on("connect", () => {
  console.log("MQTT connected");
  mqttClient.subscribe("dev/test");
});

mqttClient.on("message", (topic, msg) => {
  console.log(topic, msg.toString());
});

/* START */
server.listen(3000, () => {
  console.log("DEV server running on :3000");
});