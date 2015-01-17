#!/bin/bash

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
colden=${36}
sigden=${37}
tol=${38}
ps_mass=${39}
jobid=${40}

# Sned parameters to python script
python $base/bin/initscript.py $xi $yi $zi $vxi $vyi $vzi $sigpos $sigvel $n $timesteps $t_total $potential \
	$disk $bulge $halo $drag_optn $vfield $vzero $vRsc $denfield $nhcen $nhcen2 $Rscpow $alphapow $nhdisk1 \
	$Rscdisk1 $Zscdisk1 $nhdisk2 $Rscdisk2 $Zscdisk2 $nhdisk3 $Rscdisk3 $Zscdisk3 $colden $sigden $tol $ps_mass > swarm.out

# Run the mailer

if [[ -n $email ]]; then
    $base/bin/swarm.mailer.php $email $jobid
fi
