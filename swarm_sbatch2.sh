#!/bin/bash
#

# Load in madsdk paths

#. /opt/madsdk/bin/madsdk_init.sh

# Collect arguments

base=$1
email=$2
output_fmt=$3
xi=$4
yi=$5
zi=$6
vxi=$7
vyi=$8
vzi=$9
sigpos=${10}
sigvel=${11}
n=${12}
t_total=${13}
timesteps=${14}
potential=${15}
disk=${16}
bulge=${17}
halo=${18}
jobid=${19}

python $base/bin/test_script2.py $xi $yi $zi $vxi $vyi $vzi $sigpos $sigvel $n $output_fmt $timesteps $t_total $potential $disk $bulge $halo > swarm.out

# Run the mailer

if [[ -n $email ]]; then
    $base/bin/swarm.mailer.php $email $jobid 
fi
