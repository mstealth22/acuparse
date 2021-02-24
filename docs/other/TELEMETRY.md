# Acuparse Telemetry Collection Guide

When checking for updates, Acuparse will send a data packet containing some basic details about your system and install.
This data is necessary for planning future releases and understanding the overall install base.

Acuparse generates a unique client ID during install which is sent along with this data. Client ID and Access/Hub MAC
address used for data validation.

To view the data stored for your install, visit the admin settings page, then click on the Telemetry Data button (under
Update Checking).

The current query used when checking for updates is:

- `client=<install_id>&version=<version>&mac=<device_mac>&chassis=<chassis>&virt=<virtualization>&kernel=<kernel>&os=<operating_system>&arch=<architecture>`

## Generating Telemetry

On most installs, telemetry data is generated by executing:

```bash
hostnamectl
```

### Container

Docker Container installs do not have `hostnamectl` available. You may see an error in your logs that this command
failed. This error can be safely ignored. Acuparse manually builds update data for Container installs.