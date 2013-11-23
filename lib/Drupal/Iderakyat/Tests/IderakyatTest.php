<?php

/**
 * @file
 * Contains Drupal\Iderakyat\Tests\IderakyatTest.
 */

namespace Drupal\Iderakyat\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests Iderakyat installation profile expectations.
 */
class IderakyatTest extends WebTestBase {

  protected $profile = 'Iderakyat';

  public static function getInfo() {
    return array(
      'name' => 'Iderakyat installation profile',
      'description' => 'Tests Iderakyat installation profile expectations.',
      'group' => 'Iderakyat',
    );
  }

  /**
   * Tests Iderakyat installation profile.
   */
  function testIderakyat() {
    $this->drupalGet('');
    $this->assertLink(t('Contact'));
    $this->clickLink(t('Contact'));
    $this->assertResponse(200);

    // Test anonymous user can access 'Main navigation' block.
    $admin = $this->drupalCreateUser(array('administer blocks'));
    $this->drupalLogin($admin);
    // Configure the block.
    $this->drupalGet('admin/structure/block/add/system_menu_block:main/bartik');
    $this->drupalPostForm(NULL, array(
      'region' => 'sidebar_first',
      'id' => 'main_navigation',
    ), t('Save block'));
    // Verify admin user can see the block.
    $this->drupalGet('');
    $this->assertText('Main navigation');

    // Verify we have role = aria on system_powered_by and system_help_block
    // blocks.
    $this->drupalGet('admin/structure/block');
    $elements = $this->xpath('//div[@role=:role and @id=:id]', array(
      ':role' => 'complementary',
      ':id' => 'block-bartik-help',
    ));

    $this->assertEqual(count($elements), 1, 'Found complementary role on help block.');

    $this->drupalGet('');
    $elements = $this->xpath('//div[@role=:role and @id=:id]', array(
      ':role' => 'complementary',
      ':id' => 'block-bartik-powered',
    ));
    $this->assertEqual(count($elements), 1, 'Found complementary role on powered by block.');

    // Verify anonymous user can see the block.
    $this->drupalLogout();
    $this->assertText('Main navigation');

  }

}
