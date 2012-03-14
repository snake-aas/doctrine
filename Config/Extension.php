<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.org
 */

namespace Nella\Doctrine\Config;

/**
 * Nella Framework doctrine extension
 *
 * @author	Patrik Votoček
 */
class Extension extends \Nella\NetteAddons\Doctrine\Config\Extension
{
	/** @var array */
	public $defaults = array(
		'repositoryClass' => 'Nella\Doctrine\Repository',
	);
	
	/**
	 * Processes configuration data
	 *
	 * @throws \Nette\InvalidStateException
	 */
	public function loadConfiguration()
	{
		parent::loadConfiguration();
		
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);
		
		/**
		 *  Add some stuff to Doctrine addon for Nella Framework
		 */
		
		// Ignore Testing dir for loading entityes
		if ($builder->hasDefinition($this->prefix('metadataDriver'))) {
			$builder->getDefinition($this->prefix('metadataDriver'))
				->addSetup('addIgnoredDir', array(__DIR__ . "/../../Testing"));
		}
		
		// Set default repostitory class
		if (isset($config['entityManagers'])) {
			foreach ($config['entityManagers'] as $name => $em) {
				$cfg = $em + $this->entityManagerDefaults;

				if ($builder->hasDefinition($this->configurationsPrefix($name))) {
					$builder->getDefinition($this->configurationsPrefix($name))
						->addSetup('setDefaultRepositoryClassName', array($cfg['repositoryClass']));
				}
			}
		}
	}
}