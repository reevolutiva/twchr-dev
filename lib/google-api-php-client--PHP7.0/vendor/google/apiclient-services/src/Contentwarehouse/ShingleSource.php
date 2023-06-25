<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\Contentwarehouse;

class ShingleSource extends \Google\Model
{
  /**
   * @var int
   */
  public $id;
  /**
   * @var int
   */
  public $numShingles;
  /**
   * @var int
   */
  public $timestamp;

  /**
   * @param int
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param int
   */
  public function setNumShingles($numShingles)
  {
    $this->numShingles = $numShingles;
  }
  /**
   * @return int
   */
  public function getNumShingles()
  {
    return $this->numShingles;
  }
  /**
   * @param int
   */
  public function setTimestamp($timestamp)
  {
    $this->timestamp = $timestamp;
  }
  /**
   * @return int
   */
  public function getTimestamp()
  {
    return $this->timestamp;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ShingleSource::class, 'Google_Service_Contentwarehouse_ShingleSource');
