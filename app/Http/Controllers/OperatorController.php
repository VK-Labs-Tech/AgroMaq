<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OperatorController extends Controller
{
    public function index()
    {
        $operators = Operator::query()
            ->orderBy('name')
            ->paginate(12);

        return view('operators.index', [
            'operators' => $operators,
        ]);
    }

    public function create()
    {
        return view('operators.create', [
            'operator' => new Operator(),
        ]);
    }

    public function store(Request $request)
    {
        $operator = Operator::query()->create($this->validated($request));

        return redirect()
            ->route('operators.show', $operator)
            ->with('success', 'Operador cadastrado com sucesso.');
    }

    public function show(Operator $operator)
    {
        $operator->load([
            'workLogs' => fn ($query) => $query->latest('started_at')->limit(15),
        ]);

        return view('operators.show', [
            'operator' => $operator,
        ]);
    }

    public function edit(Operator $operator)
    {
        return view('operators.edit', [
            'operator' => $operator,
        ]);
    }

    public function update(Request $request, Operator $operator)
    {
        $operator->update($this->validated($request, $operator));

        return redirect()
            ->route('operators.show', $operator)
            ->with('success', 'Operador atualizado com sucesso.');
    }

    public function destroy(Operator $operator)
    {
        $operator->delete();

        return redirect()
            ->route('operators.index')
            ->with('success', 'Operador removido com sucesso.');
    }

    private function validated(Request $request, ?Operator $operator = null): array
    {
        $operatorId = $operator?->id;

        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'cpf' => ['required', 'string', 'max:14', Rule::unique('operators', 'cpf')->ignore($operatorId)],
            'phone' => ['nullable', 'string', 'max:30'],
            'license_number' => ['nullable', 'string', 'max:60'],
            'license_category' => ['nullable', 'string', 'max:10'],
            'license_expires_at' => ['nullable', 'date'],
            'active' => ['required', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
