const { exec } = require("child_process");

exec("php artisan queue:work --sleep=1 --tries=3 --max-time=3600", (err, stdout, stderr) => {
    if (stdout) console.log(stdout);
    if (stderr) console.error(stderr);
});