module.exports = {
  apps: [
    {
      name: "pagviva-queue",
      script: "./queue-worker.sh",

      // Execução
      exec_mode: "fork", // worker → melhor que cluster
      instances: 1,

      // Restart inteligente
      autorestart: true,
      watch: false,
      max_restarts: 10,
      restart_delay: 5000,

      // Proteção de memória
      max_memory_restart: "300M",

      // Logs
      error_file: "./logs/queue-error.log",
      out_file: "./logs/queue-out.log",
      log_date_format: "YYYY-MM-DD HH:mm:ss",

      // Ambiente
      env: {
        NODE_ENV: "production"
      }
    }
  ]
};