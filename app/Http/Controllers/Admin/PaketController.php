<?php
/**
 * @author David Bezalel Laoli <davidbezalel94@gmail.com>
 *
 * @since 8/29/17
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Kontrak;
use App\Model\Paket;
use App\Model\SubPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaketController extends Controller
{

    /**
     * @todo display a index view and return a json response of ppk data
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($this->isPost()) {
            $paketModel = new Paket();
            $subpaketmodel = new SubPaket();

            $columns = ['no', 'title', 'year', 'created_at'];
            $where = array(
                ['title', 'LIKE', '%' . $request['search']['value'] . '%']
            );
            $pakets = $paketModel->find_v2($where, true, ['*'], intval($request['length']), intval($request['start']), $columns[intval($request['order'][0]['column'])], $request['order'][0]['dir']);
            $number = intval($request['start']) + 1;
            foreach ($pakets as &$item) {
                $item['no'] = $number;
                $item['subpaket'] = SubPaket::where('paket_id', $item['id'])->get();
                $number++;
            }
            $response_json = array();
            $response_json['draw'] = $request['draw'];
            $response_json['data'] = $pakets;
            $response_json['recordsTotal'] = $paketModel->getTableCount($where);
            $response_json['recordsFiltered'] = $paketModel->getTableCount($where);
            return $this->__json($response_json);
        }
        $styles = array();
        $scripts = array();
        $scripts[] = 'paket.js';
        $this->data['styles'] = $styles;
        $this->data['scripts'] = $scripts;
        $this->data['controller'] = 'paket';
        $this->data['title'] = 'Paket';
        return view('admin.paket.index')->with('data', $this->data);
    }

    /**
     * @todo insert paket
     *
     * validate request
     * @rules: all required
     *
     * paket: insert
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        if ($this->isPost()) {
            $paketModel = new Paket();

            /**
             * @todo validate request
             */
            $rules = array(
                'title'=> 'required',
                'year'=> 'required'
            );

            if (null !== $this->validate_v2($request, $rules)) {
                $this->response_json->message = $this->validate_V2($request, $rules);
                return $this->__json();
            }

            /**
             * @todo paket: insert
             */
            try {
                DB::beginTransaction();
                $data = $request->all();
                $paket = $paketModel::create($data);

                $data = array();
                $data['paket_id'] = $paket['id'];
                $data['title'] = $paket['title'];
                $data['type'] = SubPaket::$utama;
                SubPaket::create($data);

                if (isset($request['subpakettitle'])) {
                    foreach ($request['subpakettitle'] as $key => $value) {
                        $data['title'] = $value;
                        $data['type'] = SubPaket::$bulanan;
                        SubPaket::create($data);
                    }
                }


                DB::commit();
                $this->response_json->status = true;
                $this->response_json->message = 'Paket added.';
            } catch (\Exception $e) {
                DB::rollback();
                $this->response_json->message = $this->getServerError();
            }
            return $this->__json();
        }
    }

    /**
     * @todo update specific paket
     *
     * validate request body
     * @rules: all required
     *
     * paket: update
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if ($this->isPost()) {

            $paketModel = new Paket();

            /**
             * @todo validate request
             */
            $rules = array(
                'title'=> 'required',
                'year'=> 'required'
            );

            if (null !== $this->validate_v2($request, $rules)) {
                $this->response_json->message = $this->validate_V2($request, $rules);
                return $this->__json();
            }

            /**
             * @todo ppk: update
             */
            try {
                $paket = $paketModel->find($request['id']);
                foreach ($paketModel->getFillable() as $field) {
                    $paket[$field] = $request[$field];
                }

                $paket->update();
                $this->response_json->status = true;
                $this->response_json->message = 'Paket updated.';
            } catch (\Exception $e) {
                $this->response_json->message = $this->getServerError();
            }
            return $this->__json();
        }
    }

    /**
     * @todo delete specific ppk
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        if ($this->isPost()) {
            try {
                Paket::find($request['id'])->delete();
                $this->response_json->status = true;
                $this->response_json->message = 'Paket deleted.';
            } catch (\Exception $e) {
                $this->response_json->message = $this->getServerError();
            }
            return $this->__json();
        }
    }

    /**
     * @todo return all paket
     * '
     * @return \Illuminate\Http\JsonResponse
     */
    public function get() {
        if ($this->isPost()) {
            $pakets = Paket::all();
            $this->response_json->status = true;
            $this->response_json->data = $pakets;
            return $this->__json();
        }
    }

    /**
     * @todo return all paket depend on ppk
     *
     * @param int $ppk_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getbyppk($ppk_id) {
        if ($this->isPost()) {
            $kontrakmodel = new Kontrak();
            $paketmodel = new Paket();
            $kontraks = $kontrakmodel->where('ppk_id', '=', $ppk_id)->get();
            foreach ($kontraks as $index => $value) {
                $paket = $paketmodel->find($value['paket_id']);
                $value['paket'] = $paket;
            }
            $this->response_json->data = $kontraks;
            $this->response_json->status = true;
            return $this->__json();
        }
    }

    public function getsubpaket($paket_id) {
        if ($this->isPost()) {
            $subpakets = SubPaket::where('paket_id', '=', $paket_id)->get();
            $this->response_json->data = $subpakets;
            $this->response_json->status = true;
            return $this->__json();
        }
    }
}