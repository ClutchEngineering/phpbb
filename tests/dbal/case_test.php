<?php
/**
*
* @package testing
* @copyright (c) 2012 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class phpbb_dbal_case_test extends phpbb_database_test_case
{
	public function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__).'/fixtures/config.xml');
	}

	public function test_case_int()
	{
		$db = $this->new_dbal();

		$sql = 'SELECT ' . $db->sql_case('1 = 1', '1', '2') . ' AS test_num
			FROM \phpbb\config\config';
		$result = $db->sql_query_limit($sql, 1);

		$this->assertEquals(1, (int) $db->sql_fetchfield('test_num'));

		$sql = 'SELECT ' . $db->sql_case('1 = 0', '1', '2') . ' AS test_num
			FROM \phpbb\config\config';
		$result = $db->sql_query_limit($sql, 1);

		$this->assertEquals(2, (int) $db->sql_fetchfield('test_num'));
	}

	public function test_case_string()
	{
		$db = $this->new_dbal();

		$sql = 'SELECT ' . $db->sql_case('1 = 1', "'foo'", "'bar'") . ' AS test_string
			FROM \phpbb\config\config';
		$result = $db->sql_query_limit($sql, 1);

		$this->assertEquals('foo', $db->sql_fetchfield('test_string'));

		$sql = 'SELECT ' . $db->sql_case('1 = 0', "'foo'", "'bar'") . ' AS test_string
			FROM \phpbb\config\config';
		$result = $db->sql_query_limit($sql, 1);

		$this->assertEquals('bar', $db->sql_fetchfield('test_string'));
	}

	public function test_case_column()
	{
		$db = $this->new_dbal();

		$sql = 'SELECT ' . $db->sql_case("config_name = 'config1'", 'config_name', 'config_value') . " AS test_string
			FROM \phpbb\config\config
			WHERE config_name = 'config1'";
		$result = $db->sql_query_limit($sql, 1);

		$this->assertEquals('config1', $db->sql_fetchfield('test_string'));

		$sql = 'SELECT ' . $db->sql_case("config_name = 'config1'", 'config_name', 'config_value') . " AS test_string
			FROM \phpbb\config\config
			WHERE config_value = 'bar'";
		$result = $db->sql_query_limit($sql, 1);

		$this->assertEquals('bar', $db->sql_fetchfield('test_string'));
	}
}
