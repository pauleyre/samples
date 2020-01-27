<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class YTSearchController extends AbstractController
{
    /**
     * @Route("/", name="y_t_search")
     */
    public function index(Request $request)
    {
		    $videos = '';
			$channels = '';
				
		$form = $this->createFormBuilder()
            ->add('q', TextType::class, ['label' => 'Search Term: ' ])
            ->add('maxResults', TextType::class, ['label' => 'Max Results Total: ', 'attr' => ['min'=>1, 'max'=>50, 'step'=>1]])
            ->add('save', SubmitType::class, ['label' => 'Run YouTube Search'])
			 ->setMethod('GET')
            ->getForm();
		
		$form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
		
		$formData = $form->getData();

		if ($formData['q'] && $formData['maxResults']) {

		  require_once '../vendor/google-api-php-client/src/Google_Client.php';
		  require_once '../vendor/google-api-php-client/src/contrib/Google_YouTubeService.php';

		  $client = new \Google_Client();
		  $client->setDeveloperKey('AIzaSyBbJiZQm4XRvLpa44i4u5DlNrA2mnSDpE0');

		  $youtube = new \Google_YoutubeService($client);

		  try {
			$searchResponse = $youtube->search->listSearch('id,snippet', array(
			  'q' => $formData['q'],
			  'maxResults' => $formData['maxResults'],
			));

			foreach ($searchResponse['items'] as $searchResult) {
			  switch ($searchResult['id']['kind']) {
				case 'youtube#video':
				  $videos .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
					$searchResult['id']['videoId'].'<br/><a href="http://www.youtube.com/watch?v='.$searchResult['id']['videoId'].'" target=_blank><img src="'.$searchResult['snippet']['thumbnails']['default']['url'].'" width="'.$searchResult['snippet']['thumbnails']['default']['width'].'" height="'.$searchResult['snippet']['thumbnails']['default']['height'].'"/><br/>Watch This Video</a>');
				  break;
				case 'youtube#channel':
				  $channels .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
					$searchResult['id']['channelId']);
				  break;
			   }
			}

		   } catch (\Google_ServiceException $e) {
			$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
			  htmlspecialchars($e->getMessage()));
		  } catch (\Google_Exception $e) {
			$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
			  htmlspecialchars($e->getMessage()));
		  }
		}
	}
		
		
        return $this->render('yt_search/index.html.twig', [
            'controller_name' => 'YTSearchController',
			'videos' => $videos,
			'channels' => $channels,
			'form' => $form->createView()
        ]);
    }
}
