<?

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Goutte\Client;

class Scraper extends Controller {

	/**
	* @Route("/scraper")
	*/ 
	public function indexAction(Request $request)
	{
		$client = new Client();
		$crawler = $client
					->request('GET', 'http://www.example.com')
					;

		$link = $crawler
				->selectLink('')
				->link()
				;


		return new Response(var_dump($link));

	}
}