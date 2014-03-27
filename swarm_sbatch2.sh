#!/bin/bash
#

# Load in madsdk paths

#. /opt/madsdk/bin/madsdk_init.sh

# Collect arguments

base=$1
email=$2
xi=$3
yi=$4
zi=$5
vxi=$6
vyi=$7
vzi=$8
sigpos=$9
sigvel=${10}
n=${11}
t_total=${12}
timesteps=${13}
potential=${14}
disk=${15}
bulge=${16}
halo=${17}
drag_optn=${18}
vfield=${19}
vzero=${20}
vRsc=${21}
denfield=${22}
nhcen=${23}
nhcen2=${24}
Rscpow=${25}
alphapow=${26}
nhdisk1=${27}
Rscdisk1=${28}
Zscdisk1=${29}
nhdisk2=${30}
Rscdisk2=${31}
Zscdisk2=${32}
nhdisk3=${33}
Rscdisk3=${34}
Zscdisk3=${35}
jobid=${36}

python $base/bin/initscript.py $xi $yi $zi $vxi $vyi $vzi $sigpos $sigvel $n $timesteps $t_total $potential $disk $bulge $halo > swarm.out

# Run the mailer

if [[ -n $email ]]; then
    $base/bin/swarm.mailer.php $email $jobid
fi
