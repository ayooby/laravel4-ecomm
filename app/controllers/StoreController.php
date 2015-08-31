<?php
class StoreController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth', array('only' => array('postAddToCart', 'getCart', 'getRemoveItem')));
    }

    public function getIndex()
    {
        return View::make('store.index')
            ->with('products', Product::take(4)->orderBy('created_at', 'DESC')->get());
    }

    public function getView($id)
    {
        return View::make('store.view')
            ->with('product', Product::find($id));
    }

    public function getCategory($catID)
    {
        return View::make('store.category')
            ->with('products', Product::where('category_id', '=', $catID)->paginate(6))
            ->with('category', Category::find($catID));
    }

    public function getSearch()
    {
        $keyword = Input::get('keyword');

        return View::make('store.search')
            ->with('products', Product::where('title', 'LIKE', '%' . $keyword . '%')->get())
            ->with('keyword', $keyword);
    }

    public function postAddtocart()
    {
        $product  = Product::find(Input::get('id'));
        $quantity = Input::get('quantity');
        $member_id = Auth::user()->id;

        Cart::create(array(
            'product_id' => $product->id,
            'name'       => $product->title,
            'price'      => $product->price,
            'quantity'   => $quantity,
            'image'      => $product->image,
            'member_id'  => $member_id
        ));

        return Redirect::to('store/cart');
    }

    public function getCart()
    {

$member_id = Auth::user()->id;

    $cart_products=Cart::with('Product')->where('member_id','=',$member_id)->get();

    $cart_total=Cart::with('Product')->where('member_id','=',$member_id)->sum('quantity');

   
    // return View::make('cart')
    //       ->with('cart_books', $cart_books)
    //       ->with('cart_total',$cart_total);

       return View::make('store.cart')
          ->with('cart_products', $cart_products)
          ->with('cart_total',$cart_total);

      // return View::make('store.cart')
      //      ->with('products', Cart::contents()); 
    }

    public function getRemoveitem($identifier)
    {
        $item = Cart::item($identifier);
        $item->remove();

        return Redirect::to('store/cart');
    }

    public function getContact()
    {
        return View::make('store.contact');
    }
}