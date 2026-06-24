import subprocess, sys

# Test SSH BatchMode (key-based auth)
cmd = ['ssh', '-o', 'StrictHostKeyChecking=no', '-o', 'ConnectTimeout=10',
       '-o', 'BatchMode=yes', 'root@36.50.40.224', 
       'docker ps --format "{{.Names}}" | grep -iE "maria|mysql|db"']
result = subprocess.run(cmd, capture_output=True, text=True, timeout=15)
print('stdout:', result.stdout)
print('stderr:', result.stderr[:300])
print('returncode:', result.returncode)
