namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    protected $fillable = ['gallery_album_id', 'image_path'];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class);
    }
}