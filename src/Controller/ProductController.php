<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @Route("api", name="api_")
 */
class ProductController extends BaseController
{

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * ProductService constructor.
     *
     * @param ProductService $productService
     *
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Rest\Post("/product", name="post_product")
     * @param Product $product
     * @param Request  $request
     * @return View
     * @throws \App\Response\ApiResponseException
     * @ParamConverter(
     *     "product",
     *     converter="fos_rest.request_body",
     *     options={"deserializationContext"={"groups"={"allowPosted"}, "version"="1.0"}},
     * )
     * @Rest\View(serializerGroups={"public"},serializerEnableMaxDepthChecks=1, StatusCode = 201)
     */
    public function postProduct(Product $product ,Request $request): View
    {
        $data = $request->request->all();

        return $this->view($this->productService->addProduct($product, $data));
    }

    /**
     * @Rest\Put("/product/{id}", name="put_product")
     * @param Request  $request
     * @param Product $product
     * @return View
     *
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     */
    public function putProduct(Request $request, Product $product): View
    {
        $data = $request->request->all();

        $product = $this->deserialize($request, Product::class,
            ['object_to_populate' => $product, 'groups' => 'allowPosted']
        );

        return $this->view($this->productService->updateProduct($product, $data), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/product/{productId}", name="get_product")
     * @param int      $productId
     * @return View
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     */
    public function showProduct(int $productId) : View
    {
        return $this->view($this->productService->getProduct($productId), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/product", name="get_products")
     * @return View
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     */
    public function showProducts() : View
    {
        return $this->view($this->productService->getProducts(), Response::HTTP_OK);
    }

}