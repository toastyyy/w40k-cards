<?php


namespace App\Controller;


use App\Entity\Card;
use App\Service\CardSerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cards", name="api_card_")
 */
class CardController extends AbstractController
{
    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show($id, CardSerializerInterface $cardSerializer, Request $request) {
        $card = $this->getDoctrine()->getRepository(Card::class)->find($id);
        if($card) {
            return new JsonResponse($cardSerializer->serialize($card));
        }
        return new JsonResponse(null, 404);
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     */
    public function update($id, CardSerializerInterface $cardSerializer, Request $request) {
        $card = $cardSerializer->deserialize(json_decode($request->getContent()));
        if($card && $this->getDoctrine()->getManager()->contains($card) && $card->getId() == $id) {
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse($cardSerializer->serialize($card));
        }
        return new JsonResponse(null, 400);
    }

    /**
     * @Route("", name="create", methods={"POST"})
     */
    public function create(Request $request, CardSerializerInterface $cardSerializer) {
        $card = $cardSerializer->deserialize(json_decode($request->getContent()));
        if($card) {
            if(!$this->getDoctrine()->getManager()->contains($card)) {
                $this->getDoctrine()->getManager()->persist($card);
            }
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse($cardSerializer->serialize($card));
        }
        return new JsonResponse(null, 400);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete($id) {
        $card = $this->getDoctrine()->getRepository(Card::class)->find($id);
        if($card) {
            $this->getDoctrine()->getManager()->remove($card);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(null, 204);
        }
        return new JsonResponse(null, 404);
    }
}
