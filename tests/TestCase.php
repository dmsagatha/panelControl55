<?php

namespace Tests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  use CreatesApplication, TestHelpers;

  protected $defaultData = [];

  protected function setUp(): void
  {
    parent::setUp();

    $this->withoutExceptionHandling();
  }

}