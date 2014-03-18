#!/bin/bash
#

# Load in madsdk paths

#. /opt/madsdk/bin/madsdk_init.sh

# Collect arguments

base=$1
txtfile=$2
email=$3
t_total=$4
timesteps=$5
potential=$6
disk=$7
bulge=$8
halo=$9
jobid=${10}

python $base/bin/filescript.py $txtfile $timesteps $t_total $potential $disk $bulge $halo > swarm.out

# Run the mailer

if [[ -n $email ]]; then
    $base/bin/swarm.mailer.php $email $jobid 
fi
