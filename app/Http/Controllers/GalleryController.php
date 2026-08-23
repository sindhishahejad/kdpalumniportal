namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        // Fetch all albums, including their photos, ordered by newest first
        $albums = GalleryAlbum::with('photos')->latest()->get();
        return view('gallery.index', compact('albums'));
    }
}