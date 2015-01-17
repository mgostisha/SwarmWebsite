#!/bin/bash

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
denfield=${14}
nhcen=${15}
nhcen2=${16}
Rscpow=${17}
alphapow=${18}
nhdisk1=${19}
Rscdisk1=${20}
Zscdisk1=${21}
nhdisk2=${22}
Rscdisk2=${23}
Zscdisk2=${24}
nhdisk3=${25}
Rscdisk3=${26}
Zscdisk3=${27}
tol=${28}
ps_mass=${29}
jobid=${30}

# Send parameters to python script

python $base/bin/filescript.py $txtfile $timesteps $t_total $potential $disk $bulge $halo \
$drag_optn $vfield $vzero $vRsc $denfield $nhcen $nhcen2 $Rscpow $alphapow $nhdisk1 $Rscdisk1 \
$Zscdisk1 $nhdisk2 $Rscdisk2 $Zscdisk2 $nhdisk3 $Rscdisk3 $Zscdisk3 $tol $ps_mass > swarm.out

# Run the mailer

if [[ -n $email ]]; then
    $base/bin/swarm.mailer.php $email $jobid 
fi
