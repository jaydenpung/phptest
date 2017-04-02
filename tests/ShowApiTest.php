<?php
use PHPUnit\Framework\TestCase;

/**
* Setup:
* PHPUnit 6.0.10
*
* Run (at path of show_api.php):
* phpunit --bootstrap show_api.php tests
* phpunit --bootstrap show_api.php --testdox tests
*/

class ShowApiTest extends TestCase {

    /**
     * Ensure the valid ata can be retrieved. The comparison array
     * $correctData is obtained by var_export, and verified manually.
     * This assumes that the content at $link will not be removed or changed.
     */
    public function testRetrieveData() {
        $link = "http://apims.doe.gov.my/v2/hour1_2017-03-24.html";
        $data = retrieveData($link);

        $correctData = array (
          'johor' => 
          array (
            'kota tinggi' => '39*',
            'larkin lama' => '41*',
            'muar' => '30*',
            'pasir gudang' => '39*',
          ),
          'kedah' => 
          array (
            'alor setar' => '39*',
            'bakar arang, sg. petani' => '64*',
            'langkawi' => '36*',
          ),
          'kelantan' => 
          array (
            'smk tanjung chat, kota bharu' => '52*',
            'tanah merah ' => '42*',
          ),
          'melaka' => 
          array (
            'bandaraya melaka' => '54*',
            'bukit rambai' => '50*',
          ),
          'negeri sembilan' => 
          array (
            'nilai' => '63*',
            'port dickson' => '46*',
            'seremban' => '34*',
          ),
          'pahang' => 
          array (
            'balok baru, kuantan' => '49*',
            'indera mahkota, kuantan' => '36*',
            'jerantut' => '40*',
          ),
          'perak' => 
          array (
            'jalan tasek, ipoh' => '49*',
            'kg. air putih, taiping' => '59*',
            's k jalan pegoh, ipoh' => '52*',
            'seri manjung' => '51*',
            'tanjung malim' => '35*',
          ),
          'perlis' => 
          array (
            'kangar' => '24*',
          ),
          'pulau pinang' => 
          array (
            'seberang jaya 2, perai' => '57*',
            'usm' => '56*',
          ),
          'sabah' => 
          array (
            'keningau' => '30*',
            'kota kinabalu' => '35*',
            'sandakan' => '28*',
            'tawau' => '33*',
          ),
          'sarawak' => 
          array (
            'bintulu' => '47*',
            'ilp miri' => '44*',
            'kapit' => '20*',
            'kuching' => '36*',
            'limbang' => '23*',
            'miri' => '33*',
            'samarahan' => '38*',
            'sarikei' => '34*',
            'sibu' => '31*',
            'sri aman' => '18*',
          ),
          'selangor' => 
          array (
            'banting' => '55*',
            'kuala selangor' => '39*',
            'pelabuhan kelang' => '55*',
            'petaling jaya' => '52*',
            'shah alam' => '51*',
          ),
          'terengganu' => 
          array (
            'kemaman' => '51*',
            'kuala terengganu' => '45*',
            'paka' => '45*',
          ),
          'wilayah persekutuan' => 
          array (
            'batu muda' => '43*',
            'cheras' => '34*',
            'labuan' => '32*',
            'putrajaya' => '60*',
          ),
        );

        $this->assertEquals($data, $correctData);
    }

    /**
     * Check if search by area is valid. This is assuming that the website
     * did not change or remove the content at $link.
     */
    public function testSearchArea() {
        $link = "http://apims.doe.gov.my/v2/hour3_2017-03-24.html";
        $data = retrieveData($link);
        search("area", "miri", $data);

        $expectedOutput="===================== RESULTS =====================\n\nmiri: 51*\n";

        $this->expectOutputString($expectedOutput);
    }

    /**
     * Check if search by state is valid. This is assuming that the website
     * did not change or remove the content at $link.
     */
    public function testSearchState() {
        $link = "http://apims.doe.gov.my/v2/hour3_2017-03-24.html";
        $data = retrieveData($link);
        search("state", "johor", $data);

        $expectedOutput="===================== RESULTS =====================\n\nState: johor\n\nkota tinggi: 39*\nlarkin lama: 45*\nmuar: 31*\npasir gudang: 48*\n";

        $this->expectOutputString($expectedOutput);
    }

    /**
     * Check if the entire script run and output as expected. Note that
     * $_SERVER[] is hardcoded here to simulate arguments passed to the script.
     */
    public function testRun() {
        $_SERVER["argv"][1]="state=johor";
        $_SERVER["argc"]=2;

        $this->assertTrue(run());
    }

    /**
     * Ensure the script input is case insensitive and trimmed for spaces.
     */
    public function testRunInsensitive() {
        $_SERVER["argv"][1]=" STaTE = JOHOr";
        $_SERVER["argc"]=2;

        $this->assertTrue(run());
    }
}