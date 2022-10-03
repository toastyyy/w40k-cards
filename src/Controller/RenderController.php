<?php
namespace App\Controller;

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
     * @Route("/render-card", name="render_card")
     */
    public function renderCardHtml(Request $request, PdfService $pdf) {
        $data = json_decode($request->getContent());
        if($data && isset($data->content)) {
            /* we need to replace the css variables manually since wkhtmltopdf does not support css variables */
            preg_match_all('/href="styles.(\w+).css"/m', $data->content, $matches, PREG_SET_ORDER, 0);

            if($matches && isset($matches[0])) {
                $cssContent = file_get_contents($_SERVER['HTTP_ORIGIN']. '/styles.'. $matches[0][1]. '.css');

                preg_match_all('/var\(--([\w|-]+)\)/m', $cssContent, $variables);
                $replacements = [];
                foreach($variables as $var) {
                    if(isset($var[1])) {
                        // find value defined in $data->content
                        preg_match_all('/--text-color:([^;]+);/m', $data->content, $variableValue);
                        if($variableValue && isset($variableValue[0]) && isset($variableValue[0][1])) {
                            $replacements[$var[0]] = $variableValue[0][1];
                        }
                    }
                }

                foreach($replacements as $var => $value) {
                    $cssContent = str_replace($var, $value, $cssContent);
                }

                /* replace stylesheet */
                $content = str_replace('<link rel="stylesheet" href="styles.'. $matches[0][1] .'.css">', '<style>'. $cssContent. '</style>', $data->content);
                $filename = $pdf->createPdf($content, null, '--javascript-delay 1');
                return new BinaryFileResponse($filename);
            }

        }
        return new JsonResponse(null, 400);
    }

    public function renderCard(Request $request, PdfService $pdf, KernelInterface $kernel) {
        $data = $request->getContent();
        $data = json_decode($data);

        $rendered = $this->renderView('card.html.twig', [
            'data' => $data
        ]);


        $filename = $pdf->createPdf($rendered, null, '--javascript-delay 1');

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
