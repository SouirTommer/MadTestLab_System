import os
import re
import time
import json

suspicious_patterns = [
    re.compile(r"OR 1=1", re.IGNORECASE),
    re.compile(r"DROP TABLE", re.IGNORECASE),
    re.compile(r"DROP DATABASE", re.IGNORECASE),
    re.compile(r"SELECT \* FROM mysql\.user", re.IGNORECASE)
]

log_dir = "./log/"
log_prefix = "queries.log."

def monitor_logs():
    current_log_file = None
    last_position = 0

    while True:
        log_files = sorted([f for f in os.listdir(log_dir) if f.startswith(log_prefix)], reverse=True)
        if not log_files:
            time.sleep(5)
            continue

        latest_log_file = os.path.join(log_dir, log_files[0])

        if latest_log_file != current_log_file:
            current_log_file = latest_log_file
            last_position = 0

        with open(current_log_file, "r") as f:
            f.seek(last_position)
            lines = f.readlines()
            last_position = f.tell()

        for line in lines:
            for pattern in suspicious_patterns:
                if pattern.search(line):
                    generate_alert(line)
                    break

        time.sleep(5)

def generate_alert(log_entry):
    log_entry_dict = json.loads(log_entry)
    formatted_log_entry = json.dumps(log_entry_dict, indent=4, ensure_ascii=False)
    with open("alert/alert.log", "a") as alert_file:
        alert_file.write(f"Alert - {formatted_log_entry}\n")

if __name__ == "__main__":
    monitor_logs()