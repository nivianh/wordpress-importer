<?php

namespace Botble\WordpressImporter\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\WordpressImporter\Http\Requests\WordpressImporterRequest;
use Botble\WordpressImporter\WordpressImporter;

class WordpressImporterController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        Assets::addScriptsDirectly('vendor/core/plugins/wordpress-importer/js/wordpress-importer.js')
            ->addStylesDirectly('vendor/core/plugins/wordpress-importer/css/wordpress-importer.css');

        page_title()->setTitle(__('Wordpress Importer'));

        return view('plugins/wordpress-importer::import');
    }

    /**
     * @param WordpressImporterRequest $request
     * @param BaseHttpResponse $response
     * @param WordpressImporter $wordpressImporter
     * @return BaseHttpResponse
     */
    public function import(
        WordpressImporterRequest $request,
        BaseHttpResponse $response,
        WordpressImporter $wordpressImporter
    )
    {
        $validate = $wordpressImporter->verifyRequest($request);

        if ($validate['error']) {
            return $response
                ->setError()
                ->setMessage($validate['message']);
        }

        $result = $wordpressImporter->import();

        return $response
            ->setMessage(__('Imported :posts posts, :pages pages, :categories categories, :tags tags, and :users users successfully !',
                $result));
    }
}
