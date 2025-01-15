use Illuminate\Validation\Rule;

// Di dalam method rules()
public function rules()
{
    return [
        'tanggal' => [
            'required',
            'date',
            Rule::unique('teaching_activities')
                ->where(function ($query) {
                    return $query->where('guru_id', auth()->id());
                })
                ->whereDate('tanggal', $this->tanggal)
        ],
        // rules lainnya...
    ];
} 