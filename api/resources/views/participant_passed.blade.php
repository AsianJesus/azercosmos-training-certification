<h4>
    {{ $participant['participant']['NAME'] }} has passed {{ $trainingName }}
</h4>
<p>
    @if($is_test)
        {{ $participant['participant']['NAME'] }} got {{ $participant['score'] }} of {{ $pass_score }}
    @else
        {{ $participant['participant']['NAME'] }} was examined by {{ $participant['examiner']['NAME'] }}
    @endif
</p>