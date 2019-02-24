<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\WebAsset;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\WebAsset\Exception\UnknownAssetException;

/**
 * Web Asset Registry interface
 *
 * @since  __DEPLOY_VERSION__
 */
interface WebAssetRegistryInterface
{
	/**
	 * Get an existing Asset from a registry, by asset name.
	 *
	 * @param   string  $name  Asset name
	 *
	 * @return  WebAssetItem
	 *
	 * @throws  UnknownAssetException  When Asset cannot be found
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function get(string $name): WebAssetItemInterface;

	/**
	 * Add Asset to registry of known assets
	 *
	 * @param   WebAssetItemInterface  $asset  Asset instance
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function add(WebAssetItemInterface $asset): self;

	/**
	 * Remove Asset from registry.
	 *
	 * @param   string  $name  Asset name
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function remove(string $name): self;

	/**
	 * Check whether the asset exists in the registry.
	 *
	 * @param   string  $name  Asset name
	 *
	 * @return  bool
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function exists(string $name): bool;

}

