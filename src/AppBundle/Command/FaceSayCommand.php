<?php
// src/AppBundle/Command/FaceSayCommand.php 
//"*Command.php"
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FaceSayCommand extends Command
{
    protected function configure()
    {
        $this
        ->setName('concert:faceSay') // .../concert/ php bin/console app:kyrrCmd
        ->setDescription('Make faces say things')
        ->setHelp('NO HELP FOR U')
        ->addArgument('toSay', InputArgument::OPTIONAL, 'face should say this')
        ->addOption('face', 'f', InputOption::VALUE_REQUIRED, 'which face', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$toSay = $input->getArgument('toSay');
    	$face = $input->getOption('face');
    	$didDefault = false;
    
    	if(strlen($toSay) > 0){
	    	if(!$face){
	    		$output->writeln([
			        '<info>Welcome to LennySay</info>',
			        '<comment>==================</comment>',
			        '          ('.$toSay.') ',
			        '          /     ',
			        ' ( ͡° ͜ʖ ͡°)',
			        '<comment>==================</comment>',
		    	]);
	    	}else{
	    		if(is_numeric($face)){
	    			$output->writeln([ 	
	    			'<info>Welcome to faceSay</info>',
	    			'<comment>==================</comment>',
	    		]);
	    		}else{
		    		$output->writeln([ 	
		    			'<info>Welcome to '.$face.'Say</info>',
		    			'<comment>==================</comment>',
		    		]);
	    		}
	    		switch ($face) {
	    			case 'sad':
	    			case 'sadness':
	    			case 1:
	    				$output->writeln([
			        '          ('.$toSay.') ',
			        '          /     ',
			        ' ( ͡° ʖ̯ ͡°)',
		    	]);
	    				break;
	    			case 'shock':
	    			case 'disapproval':
	    			case 'anger':
	    			case 2:
	    				$output->writeln([
			        '          ('.$toSay.') ',
			        '          /     ',
			        '       ಠ_ಠ',
		    	]);
	    				break;
	    			case 'song':
	    				$output->writeln([
			        '          ('.$toSay.') ',
			        '          /     ',
			        '    ヽ(⌐■_■)ノ♪♬',
		    	]);
	    				break;
	    			case 'bear':
	    				$output->writeln([
			        '          ('.$toSay.') ',
			        '          /     ',
			        '       ʕ•ᴥ•ʔ',
		    	]);
	    				break;
	    			default:
	    				$output->writeln(['Default']);
	    				$didDefault=true;
	    				break;
	    		}
	    		if(!$didDefault){
	    			$output->writeln('<comment>==================</comment>');
	    		}
	    	}
	    	
	    }
    }
}
