<?php
namespace App\Controller;

use App\Entity\Card;
use App\Service\PdfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class RenderController extends AbstractController
{
    /**
     * @Route("/render-card/{id}", name="render_card")
     */
    public function renderCardHtml(Request $request, PdfService $pdf, $id) {
        $data = json_decode($request->getContent());
        $card = $this->getDoctrine()->getRepository(Card::class)->find($id);
        if($data && isset($data->content) && $card) {
            $content = $data->content;
            $content = str_replace('<link rel="stylesheet" href="styles.', '<link rel="stylesheet" href="'. $this->getParameter('frontend_url') . '/styles.', $content);
            $re = '/style\="--base-size:([^"])+"/m';
            $options = [
                'margin' => ['top' => '0', 'left' => '0', 'right' => '0', 'bottom' => '0'],
                'scale' => 0.6
            ];
            if($card->getBig()) {
                $options['landscape'] = true;
                //$options['format'] = 'A4';
            } else {
                //$options['format'] = 'A4';
            }
            $subst = "style=\"--base-size:50px;\"";
            $content = preg_replace($re, $subst, $content);


            $filename = $pdf->createPdf($content, null, $options);
            return new BinaryFileResponse($filename);
        }
        return new JsonResponse(null, 400);
    }

    public function renderCard(Request $request, PdfService $pdf, KernelInterface $kernel) {
        $data = $request->getContent();
        $data = json_decode($data);

        $rendered = $this->renderView('card.html.twig', [
            'data' => $data
        ]);


        $filename = $pdf->createPdf($rendered, null);

        return new BinaryFileResponse($filename);
    }

    public function sample() {
        $data = json_decode("{
            \"wounds\": 2,
            \"movementSpeed\": \"5''\",
            \"image\": \"\",
            \"colorDark\": \"#1e3202\",
            \"color\": \"#597b29\",
            \"colorLight\": \"#8ea967\",
            \"weaponSkill\": \"3+\",
            \"ballisticSkill\": \"3+\",
            \"strength\": \"3\",
            \"toughness\": \"5\",
            \"attack\": 2,
            \"save\": \"4+\",
            \"leadership\": 7,
            \"infos\": [
                { \"title\": \"Test\", \"text\": \"Lorem Ipsum\" }
            ],
            \"unitName\": \"Intercessor Squad\",
            \"points\": 200,
            \"attacks\": [
                { \"name\": \"Name des Angriffs\", \"type\": \"Assault 1\", \"strength\": 4, \"penetration\": 0, \"damage\": 1, \"distance\": \"24''\", \"abilities\": \"Plague Weapon\" }
            ],
            \"abilities\": [
                { \"name\": \"Lorem Ipsum\", \"text\": \"Lorem Ipsum Dolor\" }
            ]
        }");
        return $this->render('card.html.twig', [
            'data' => $data
        ]);
    }
}
