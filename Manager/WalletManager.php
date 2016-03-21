<?php

namespace Oidnus\WalletBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Oidnus\WalletBundle\Entity\Transaction;
use Oidnus\WalletBundle\Entity\Wallet;

class WalletManager
{
	const STATE_ADD = 1;
	const STATE_REMOVE = 2;

	protected $em;

	public function __construct(ObjectManager $objectManager){
		$this->em = $objectManager;
	}

	public function add($username,$amount,$desc){
		return $this->change($username,self::STATE_ADD,$amount,$desc);
	}

	public function remove($username,$amount,$desc){
		return $this->change($username,self::STATE_REMOVE,$amount,$desc);
	}

	public function getReport($username,$date){
		return $this->em->getRepository('OidnusWalletBundle:Transaction')
			->getReport($username,$date)
			->getResult();
	}
	
	private function change($username,$state,$amount,$desc){
		$wallet = $this->em
			->getRepository('OidnusWalletBundle:Wallet')
			->findOneByUsername($username);

		if (!$wallet){
			$wallet = new Wallet();
			$wallet->setUsername($username);
		}

		$trans = new Transaction();
		$trans->setUsername($username);
		$trans->setDescription($desc);
		$trans->setAmount($amount);
		$trans->setState($state);

		if ($state === self::STATE_ADD){
			$wallet->setBalance($wallet->getBalance()+$amount);
		}elseif ($state === self::STATE_REMOVE){
			$wallet->setBalance($wallet->getBalance()-$amount);
		}

		$this->em->persist($trans);
		$this->em->persist($wallet);
		$this->em->flush();

		return true;
	}


}