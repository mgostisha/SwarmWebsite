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
drag_optn=${10}
vfield=${11}
vzero=${12}
vRsc=${13}
rhofield=${14}
rhocen=${15}
rhocen2=${16}
Rscpow=${17}
alphapow=${18}
rhodisk1=${19}
Rscdisk1=${20}
Zscdisk1=${21}
rhodisk2=${22}
Rscdisk2=${23}
Zscdisk2=${24}
rhodisk3=${25}
Rscdisk3=${26}
Zscdisk3=${27}
jobid=${28}

python $base/bin/filescript.py $txtfile $timesteps $t_total $potential $disk $bulge $halo > swarm.out

# Run the mailer

if [[ -n $email ]]; then
    $base/bin/swarm.mailer.php $email $jobid 
fi
