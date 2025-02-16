<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        return view('index', ['categories' => $categories]);
}
    public function confirm(ContactRequest $request)
    {
        $contact = $request->only([
            'last_name', 'first_name', 'gender', 'email', 'tell1', 'tell2', 'tell3', 'address', 'building', 'category_id', 'detail'
        ]);

        $contact['name'] = $contact['first_name'] . ' ' . $contact['last_name'];
        $contact['tell'] = $contact['tell1'] . $contact['tell2'] . $contact['tell3'];
        $contact['gender_label'] = $this->getGenderLabel($contact['gender']);
        $category = Category::find($contact['category_id']);
        $contact['category_label'] = $category ? $category->content : '未選択';

        return view('confirm', ['contact' => $contact]);
    }

    public function getGenderLabel($gender)
    {
        $gender = (int)$gender;

        switch ($gender) {
            case 1:
                return '男性';
            case 2:
                return '女性';
            case 3:
                return 'その他';
            default:
                return '不明';
        }
    }

    public function store(Request $request)
    {
        if ($request->has('back')) {
            return redirect('/')->withInput();
        }

        $contact = $request->only([
            'last_name', 'first_name', 'gender', 'email', 'tell', 'address', 'building', 'category_id', 'detail'
        ]);

        Contact::create($contact);

        return view('thanks');
    }
}