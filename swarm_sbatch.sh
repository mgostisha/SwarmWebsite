#!/bin/bash
#

# Load in madsdk paths

#. /opt/madsdk/bin/madsdk_init.sh

# Collect arguments

base=$1
txtfile=$2
email=$3
output_fmt=$4
t_total=$5
timesteps=$6
potential=$7
disk=$8
bulge=$9
halo=${10}
jobid=${11}

python $base/bin/test_script.py $txtfile $output_fmt $timesteps $t_total $potential $disk $bulge $halo > swarm.out

# Run the mailer

if [[ -n $email ]]; then
    $base/bin/swarm.mailer.php $email $jobid 
fi
